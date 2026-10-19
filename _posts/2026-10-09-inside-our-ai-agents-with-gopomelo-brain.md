---
title: "Inside Our AI Agents with GoPomelo Brain"
description: "Building an agent has never been easier. The hard problem was never how to build an agent — it's how to hand one your company, and how to trust what it hands back."
categories:
  - Tech
tags:
  - AI
  - Agents
  - GoPomelo
  - Knowledge Base
  - Architecture
  - Engineering
schema:
  - Article
  - Person
canonical_url: https://www.gopomelo.com/blog/inside-our-ai-agents-with-gopomelo-brain
---
*Originally published on the [GoPomelo blog](https://www.gopomelo.com/blog/inside-our-ai-agents-with-gopomelo-brain).*

*by Bryan Chua*

![Inside Our AI Agents with GoPomelo Brain — colorful concentric knowledge graph on a warm ivory background](/assets/images/gopomelo-brain-knowledge-graph.png)

Building an agent has never been easier. Frameworks, runtimes, managed platforms — pick one and you'll have something running before lunch.

Every one of them ships with the same hole in the middle. The agent knows the world and knows nothing about your organization: not your processes, not who signs off on what, not the reasons behind either. None of it was ever on the internet to be trained on. It lives in the people who work there, and in the documents those people forgot they wrote.

So the hard problem was never how to build an agent. It's how to hand one your company — and how to trust what it hands back.

Every AI agent demo works. That's what makes it a demo — someone picked the question in advance. A few weeks ago, I asked our AI agent, built with our very own custom-built knowledge base, who the CTO of GoPomelo is. The agent told me, with complete confidence, that the answer was a “CTO consulting firm” whose registered company name happens to begin with the letters C-T-O.

## Down the rabbit hole – building a new brain for agents

I am the CTO of GoPomelo. The AI agent’s knowledge base had every customer and organization chart it needed. It just liked the other answer better, because about a third of our data is customer pages, and for almost any role word you can think of, some company in Southeast Asia is registered under it. Asking who the CEO is returned a different company entirely — one whose entity name also starts with three familiar letters.

![Inside Claude session without GoPomelo Ontology](/assets/images/claude-session-without-gopomelo-ontology.png)

*Inside Claude session without GoPomelo Ontology*

The organization chart is the easy part, which is exactly what makes it a useful canary. An agent that earns its place inside a company has to carry far more than a reporting line. It needs the processes, the people who own each decision, and the standing procedures wrapped around them. The questions that actually matter look less like who is the CTO and more like how do I score deal risk under our finance process before this claim, transaction, or agreement gets approved. That answer isn't on one page. It's spread across a policy document, a finance SOP, and whoever last moved the thresholds — and getting it wrong doesn't embarrass you, it costs you and your company.

If the agent will confidently hand me a customer record when I ask for my own job title, it has no business anywhere near an approval workflow.

![Hermes Agent with GoPomelo Brain answering correctly](/assets/images/hermes-agent-with-gopomelo-brain.png)

*Hermes Agent with GoPomelo Brain answering correctly*

We ran thirteen role-style questions through it. Thirteen came back confident. Zero came back as "I don't know."

*The fix wasn't better retrieval. It was teaching the thing to shut up — and that turned out to be the whole architecture in miniature.*

## Three projects, one objective

At GoPomelo we run three systems, all on Google Workspace and Google Cloud.

![Inside GoPomelo AI Agents (agents governed, tokens managed & data unified)](/assets/images/gopomelo-ai-agents-governed-stack.png)

*Inside GoPomelo AI Agents (agents governed, tokens managed & data unified)*

**GoPomelo Brain** is our own custom-built knowledge base — an Obsidian-style wiki compiled from raw exports of Drive, Zendesk, Salesforce, Chat, and the Workspace directory. It serves from Cloud Run in asia-southeast3, Bangkok, with the custom domain fronted out of Singapore, because Cloud Run domain mapping is only offered in asia-southeast1. Region choice in this part of the world is rarely clean — you pick where the data should live, then find out which managed features followed you there.

Inspired by Andrej Karpathy's [LLM Wiki](https://gist.github.com/karpathy/442a6bf555914893e9891c11519de94f), our architecture has three layers, with immutable raw sources at the bottom, an LLM-authored markdown wiki compiled on top, and a schema document that tells the model how to maintain it. The argument underneath is that the wiki should be a compounding artifact rather than something re-derived from scratch on every query. Human-run wikis rot because the upkeep outgrows the payoff; as Karpathy puts it, "LLMs don't get bored." Ours is built that way — *raw/* is never edited by hand, *wiki/* is written by the model, and a schema file governs both.

**Routero AI** is the model layer. It decides which model each call goes to and keeps token cost and throughput inside a budget, so nothing above it hardcodes a provider and nobody has to guess at next month's bill. Cost control is not a dashboard you check after the fact; it belongs on the path the call actually takes.

**Agent Tower** orchestrates the Hermes agents — every GoPomelo employee gets a private, persistent agent that lives in Google Chat, Gmail, Calendar, Docs, and Drive, while IT keeps identity, audit, and recovery. Four services on Cloud Run, a Postgres 16 instance on Cloud SQL, and one GCE VM. All of these exist because I needed somewhere to run Hermes and OpenClaw earlier in 2026, which means it was a harness around someone else's runtime from the first commit. It has stayed that shape deliberately.

Three different problems: what the agent knows, what it costs, and what it is allowed to do. One objective — make an agent genuinely useful inside a company without losing control of any of the three.

That last choice was pure arithmetic. The agent needs an always-on cron and learning loop, which on Cloud Run means always-allocated CPU: roughly $137 per user per month, about $2,750 for twenty people. One *e2-standard-4* runs the whole fleet for about $120 flat. Serverless is a default, not a religion.

## Where we put the seam

Every architecture has a narrow waist somewhere — the one thin contract everything has to pass through, that lets both halves change without asking permission from the other. Most AI stacks put it at retrieval. We put ours at identity.

![One request’s path through the stack](/assets/images/request-path-through-the-stack.png)

*One request’s path through the stack*

Nothing inside Agent Tower talks to Google or to a model directly. Three internal brokers do: a workspace broker that is the only thing permitted to call *googleapis.com*, an LLM gateway that is the only thing permitted to reach a model — it hands every call to Routero AI — and a knowledge broker that is the only thing permitted to search the Brain. The agent reaches all three over plain HTTP carrying one header — a short-lived signed token whose payload is a user UUID, a purpose, and an expiry, minted every five minutes.

The purpose field matters more than it looks. A token for the workspace broker will not open the LLM gateway. The signing secret lives only in the bridge process; the per-employee workers never hold it.

What that buys is enforcement that doesn't depend on the agent behaving. Four things follow.

**Writes are denied by default.** Reads pass. Every other method is refused unless a rule matches and its environment flag is explicitly on. Five write rules exist today — Gmail send, Calendar insert, Calendar patch, Docs batchUpdate, Chat send. Drive and Sheets writes have no rule at all, and the comment in the code says why: the blast radius of an agent rewriting a finance spreadsheet means we'd rather force the conversation than ship the surface.

**Authorization lives in the query, not around it.** The knowledge broker computes which spaces a user may see from the verified token, then pushes that set into the **SQL WHERE** clause — **space_id = ANY($spaces)** — rather than filtering results afterward. The function that resolves the authorized set returns an empty list when nothing trusted yields an enabled space, and an empty list retrieves nothing. The request body can never widen it.

**Every call is attributable.** One audit table carries user, action, resource, result, tokens in and out, and cost. Workspace calls await that write before returning, so Cloud Run can't reap the instance mid-write. The nice side effect: the LLM gateway's daily budgets are computed by summing the same rows it writes. The meter and the ledger are the same object.

**Cost is a policy, not a postmortem**. Every model call leaves through the gateway and lands on Routero AI, which chooses the model and holds the call inside a budget. The same signed token rides along, so spend is attributable per employee before it is ever aggregated per company. An agent that starts looping meets a ceiling instead of an invoice. This is the part most teams bolt on after their first surprising month, and it is far easier to put on the path the call actually takes than to reconstruct afterwards from provider exports.

![Three ceilings, one meter at Routero AI](/assets/images/routero-three-ceilings-one-meter.png)

*Three ceilings, one meter at Routero AI*

Underneath all of it, **isolation is OS-level rather than container-level**: one Linux UID per employee, a home directory at mode 0700, one systemd unit each. A fully compromised agent reaches that employee's own data and stops there. The blast radius is one person, not the company.

## The half that knows nothing about you

Here is the part that looks like a bug and is actually the design.

GoPomelo Brain does not know who you are. It authenticates you — Google OIDC with a hosted-domain check, or a per-user bearer token for machine clients — and then it stops. There is no per-document permission model. Any signed-in colleague reads the same corpus. Access control happens earlier, at ingest and deploy: anything restricted is excluded from the index and never enters the image at all.

Its analytics go further. The module refuses to accept an email address. It records that a search happened, never what was asked, never by whom, and a test asserts that no value written anywhere contains an *@*.

That is the exact opposite of the audit trail Agent Tower keeps, in the same company, on purpose. Attribution is the broker's job. The Brain's job is to be a corpus with a stable contract — and a component that doesn't know who's calling is a component anyone can call.

Retrieval underneath is unfashionable: no embeddings, no vector index, no reranker. An IDF-weighted keyword search scores results over a 34,000-row tab-separated catalog, with hand-tuned priors that rank a concept page above a raw export. It opens exactly one file, follows at most one link, and typically answers in under half a second.

And it refuses to answer. When confidence falls below a floor, it returns an explicit *no_match* — zero model calls, standard-library imports only, no network client in the module at all.

That gate exists because of the incident at the top of this post, not because of a principle. There was a downstream cost too: one production turn burned 35 tool calls and took about four minutes, because the agent above had no stop signal. *no_match* is that signal.

*A retriever with no way to say "I don't know" isn't cautious or reckless. It's just a very fast way to be wrong in a new voice each time.*

## What actually survives a swap

Which brings me to the point of building it this way.

Agent Tower is one implementation of the agent layer, and the easiest way to see that is to look at where it came from. I built it to run Hermes and OpenClaw — two runtimes that were not mine, picked at a point when it wasn't remotely obvious which approach would win. Agent Tower has been a wrapper around a replaceable engine since the day it existed. It has already been swapped once, internally, before any client ever saw it.

Which makes replacing it with [Gemini Enterprise](https://cloud.google.com/gemini-enterprise) the same move a third time, not a migration. A client whose procurement has already cleared Google can drop Gemini Enterprise into that slot and keep everything underneath it — the brokers, the audit log, the write policy, the Brain, the data residency. Another client will want to build their own. The slot does not care, and that is the entire point of putting it there.

![GoPomelo Brain](/assets/images/gopomelo-brain-diagram.png)

*GoPomelo Brain*

The GoPomelo Brain is genuinely portable. It speaks MCP over streamable HTTP with two read-only tools, *brain_ask* and *wiki_read*, gated by per-user bearer tokens where only the hash is stored. The same JSON contract is available from the CLI, and there's an Open Knowledge Format export beside it. Every response carries every key — a caller never has to guard for a missing field. Point a different agent platform at that endpoint and it works today.

The brokers are portable for the same reason. The gateway, the database schema, the audit log, the write policy, the per-UID isolation — none of it knows which agent is calling. It only knows a signed token arrived with a user and a purpose on it. Swap the agent, implement against the same contracts, and the authorization model, the audit trail, and the isolation boundary all come along unchanged.

![Accessing GoPomelo Brain via MCP with OpenCode](/assets/images/gopomelo-brain-mcp-opencode.png)

*Accessing GoPomelo Brain via MCP with OpenCode*

Routero AI makes the same point one layer down. Which model runs is a config decision rather than a code path — an admin changes it from a dashboard and the agents pick it up at boot, no redeploy. Swap Agent Tower for Gemini Enterprise and Routero carries on unchanged, because it was never coupled to what was calling it either. The cost ceiling and the per-employee spend trail survive the swap for exactly the same reason the audit log does.

Every layer in this stack is replaceable. The agent is just the one we expect to replace first.

---

*I'm CTO at [GoPomelo](https://www.gopomelo.com/), where we build AI agents that carry a company's processes, decisions, and knowledge.*

