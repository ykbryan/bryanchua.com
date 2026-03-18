---
title: "The Coding Agent Arms Race: Which AI Actually Belongs in Your Terminal?"
description: "I used to be a startup CTO, and I still evaluate developer tools through one lens: do they reduce friction and help teams move? Here’s my practical take on Codex, Claude Code, Gemini CLI, Gemini Code Assist, and Jules—and why I still personally prefer Codex."
categories:
 - Tech
tags:
 - AI
 - Engineering
 - Developer Tools
 - Startups
 - Productivity
---
I used to be a startup CTO, and I still evaluate developer tools through one lens: do they reduce friction and help teams move? Here’s my practical take on Codex, Claude Code, Gemini CLI, Gemini Code Assist, and Jules—and why I still personally prefer Codex.


I used to be a startup CTO.

That means I still look at tools the same way I did back then: not as demos, not as benchmark winners, and definitely not as toys for tech Twitter.

I look at them as leverage.

Do they help a team move faster? Do they reduce friction? Do they make it easier to go from idea to implementation without adding more process, more context switching, or more noise?

That’s why the current coding-agent wave matters.

We’ve moved past the autocomplete era. The real shift now is that every major AI lab wants to own the full developer workflow. Not just suggest code in an editor, but live in your terminal, inspect your repository, edit files, run tests, explain architecture, and increasingly act like a real software teammate.

Today, the major players are clear enough:

- OpenAI Codex: https://openai.com/codex/
- Claude Code: https://www.anthropic.com/claude-code
- Gemini CLI: https://github.com/google-gemini/gemini-cli
- Gemini Code Assist: https://codeassist.google/
- Jules: https://jules.google/

That is exciting.

It is also a little ridiculous.

Because now every serious model company wants to become your coding interface.

And as with every platform shift, the market is quickly filling up with overlapping claims, half-true narratives, and too many people pretending one tool has already won.

It hasn’t.

## The wrong question

The wrong question is: **which coding agent is best?**

The better question is: **best for whom, for what workflow, and at what stage?**

These tools are not identical. They have different personalities, different strengths, and different failure modes.

My own mental model is pretty simple:

- **Codex** is the builder
- **Claude Code** is the thinker
- **Gemini** is the broad-context platform play

That is obviously an oversimplification.

But it is still useful.

## Codex: the one I reach for first

Let me start with my bias.

I still personally prefer Codex.

![Codex Usage Dashboard](/assets/images/codex-usage-limit.jpg)
Picture 1 - Hitting my weekly usage limit on OpenAI Codex


Not because I think it is objectively the best at everything. It isn’t.

I prefer it because it fits the way I like to work:

- direct
- fast
- practical
- low friction
- execution-oriented

Some tools want to discuss the problem.
Some tools want to admire the architecture.
Some tools want to prove how thoughtful they are before touching anything.

Codex usually just wants to move.

And in product-building environments, that matters more than people admit.

A lot of real work is not elegant greenfield architecture. It is:

- prototyping new ideas quickly
- shipping internal tools
- patching things under time pressure
- turning rough intent into working output
- keeping momentum without losing a day to tooling overhead

In those moments, I want a tool that helps me close the gap between thought and execution.

That is where Codex feels strongest.

### Where Codex is strongest

- building quickly
- implementing scoped features
- founder-speed prototyping
- short iteration loops
- moving from idea to something usable fast

### Where you still need discipline

- it can be overly confident
- it can move too fast for teams without review discipline
- it is not always the tool I would pick first for the deepest architectural reasoning

But for builders, operators, and leaders who still like to get their hands dirty, it is a very compelling default.

I understand why people like Peter Steinberger prefer it too. Some tools just match your operating style.

## Claude Code: the strongest reader in the room

If Codex is the builder, Claude Code is the thoughtful systems engineer.

![Claude Pro Receipt](/assets/images/claude-pro-receipt.jpg)
Picture 2 - Putting my money where my mouth is with Claude Pro to test Claude Code

Claude Code feels strongest when the task is not “build this now,” but rather:

- understand this codebase
- trace the dependencies
- untangle this architecture
- review this carefully
- make a deep change without breaking everything

This is where Claude is genuinely impressive.

It is often better at reading messy systems, holding intent across a large codebase, and reasoning before acting. That makes it extremely attractive for senior engineers, architects, and teams that spend a lot of time maintaining complexity rather than just creating new features.

### Where Claude Code is strongest

- large refactors
- architecture reasoning
- code review
- legacy systems
- understanding intent before implementation

### Tradeoff

The same thing that makes Claude strong can also make it feel heavier.

Sometimes that is exactly what you want.

Sometimes you just want the thing shipped.

If your environment is mature, complex, or high-risk, Claude Code may be the safer first choice. If your environment is speed-sensitive and execution-heavy, it may occasionally feel like one layer too much.

## Gemini: broad context, big ecosystem, less clean mental model

![Gemini API Usage Dashboard](/assets/images/gemini-api-usage.jpg)
Picture 3 - Tracking heavy multimodal context windows via Gemini API

Google’s position is interesting because it is not really one product.

It is a family:

- Gemini CLI
- Gemini Code Assist
- Jules

That already tells you what Google is doing. This is not just a coding tool. It is a broad platform bet.

Google’s real strength here is context scale and ecosystem depth.

If your workflow spans large repositories, documentation, research, product context, and Google’s broader cloud stack, Gemini becomes compelling in a different way. It is less of a single sharp tool and more of an operating surface.

### Where Gemini is strongest

- large-context workflows
- code plus docs plus product context
- teams already deep in Google’s ecosystem
- organizations thinking beyond just terminal coding

### Tradeoff

The product story is still less clean than Codex or Claude Code.

When someone says “I use Codex” or “I use Claude Code,” I know roughly what they mean.

When someone says “I use Google’s coding stack,” I still need a follow-up question.

That does not make it weak. But it does make it less crisp.

Over time, that may change.

## So which one is best for which person?

This is the part that matters.

### If you are a founder, ex-startup CTO, or operator who still builds

Start with **Codex**.

Why?

Because speed compounds. Low friction compounds. If your day is a mix of product decisions, technical experimentation, quick implementation, and constant context switching, Codex is the tool most likely to help you move without adding drag.

### If you are a senior engineer, architect, or technical lead managing complexity

Start with **Claude Code**.

Why?

Because once systems become large and fragile, understanding matters more than enthusiasm. Claude Code is often the better fit when the cost of misunderstanding the system is high.

### If you are an enterprise leader or deeply aligned with Google’s ecosystem

Take a serious look at **Gemini CLI, Gemini Code Assist, and Jules**.

Why?

Because large context, integrated workflows, and ecosystem fit matter more at enterprise scale than most teams realize.

### If you are managing a team

Do not force one tool on everyone too early.

This is where many companies will make the wrong call.

These are not just model decisions. They are workflow decisions. One tool may be better for prototyping. Another may be better for refactoring. Another may be better for code review, technical investigation, or documentation-heavy engineering.

Standardizing too early is a good way to reduce optionality before you actually understand how your team works best.

## My own conclusion

We now live in a world where every major AI company wants to become your developer interface.

Not just your assistant.

Your interface.

That is a real shift.

And it means technical leaders need better judgment, not just better prompts.

You cannot just adopt the loudest tool.
You cannot just follow benchmark screenshots.
You cannot just assume the smartest demo translates into the highest team velocity.

You have to ask:

- does this help us move?
- does this improve judgment or just increase output?
- does this reduce friction or create another layer of workflow noise?
- does this fit how we actually build?

For me, today, the answer is still Codex.

Not because it wins every category.

But because it fits the way I like to work.

It is fast. It is practical. It gets out of the way.

And after years of building products, leading teams, and trying to keep execution honest, I have learned that the best tools are usually not the ones that impress you the most in a demo.

They are the ones that remove drag.

There are now many tools for coding.

That isn’t the problem.

The real question is whether you know which one belongs in your hands.
