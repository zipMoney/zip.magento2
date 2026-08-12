# Putting a branch on the QA store

How to get a change from this repository in front of QA, and how to tell what is
already there. Linked from [README.md](README.md).

There is a Magento 2 store standing by that runs this module against Zip's
sandbox, so you can click through a real checkout:

| | |
|---|---|
| Shop | <https://zip-magento2-qa-store.dev.au.edge.zip.co/> |
| Admin | <https://zip-magento2-qa-store.dev.au.edge.zip.co/admin> |
| Test product | <https://zip-magento2-qa-store.dev.au.edge.zip.co/zip-qa-test-product.html> |

**There is only one, and everyone shares it.** Putting your branch on it takes it
away from whoever was using it. Have a look at what is on it first — see
[Which version is on the store](#which-version-is-on-the-store).

## If it is your own branch

**1. Push your branch.**

**2. Open the pipeline list of this repository:**

<https://gitlab.com/zip-au/plugins/zip-magento-2/-/pipelines>

*(this project → left sidebar **Build → Pipelines**)*

Look for the entry named **`QA stand: <your branch>`**. If there is none yet,
create one — pick your branch in *Run for*, then press **Run pipeline**:

<https://gitlab.com/zip-au/plugins/zip-magento-2/-/pipelines/new>

*(this project → left sidebar **Build → Pipelines** → button **Run pipeline**,
top right)*

Nothing starts on its own, so pressing Run is safe: you get a pipeline holding a
single button and nothing else.

**3. Press ▶ on `deploy-to-qa-stand`** (stage `qa-stand`).

That builds *your branch* into the store's image. Expect it to take longer than
the other three stands: the image runs `setup:di:compile` and
`setup:static-content:deploy` at build time, so that a pod starts fast.

**4. Open the second pipeline.** When the job finishes it links to one in the
store's own repository — that is where the image was actually built. Follow that
link, or go there yourself:

<https://gitlab.com/zip-au/plugins/zip.magento2.qa.store/-/pipelines>

*(project `zip.magento2.qa.store` → left sidebar **Build → Pipelines**; it is a
different project from this one)*

**5. Press ▶ on `deploy-dev`** there. That is the press that changes the store.

Two presses rather than one is on purpose. The first only prepares your version;
the second is the one that takes the store over.

Give it a few minutes after the second press. Magento installs itself on first
start, and the store is kept out of the load balancer until that finishes — so a
"503" straight after deploying means it is still working, not that something is
wrong.

## If it is somebody else's branch, a tag, or one specific commit

Use the form instead:

<https://gitlab.com/zip-au/plugins/zip.magento2.qa.store/-/pipelines/new>

*(project `zip.magento2.qa.store` → left sidebar **Build → Pipelines** → button
**Run pipeline**, top right)*

Three fields matter:

- **Run for branch name or tag** — leave it on `main`. This one belongs to the
  store itself and has nothing to do with the module.
- **`PLUGIN_REF_PICK`** — a short list of the usual choices. Defaults to
  `master`.
- **`PLUGIN_REF`** — type anything here: a branch, a tag, a commit. Whatever you
  type **beats** the list above.

Then press ▶ on `deploy-dev` in the pipeline it creates.

You can hand someone a link with the branch already filled in — useful in a
ticket:

```
https://gitlab.com/zip-au/plugins/zip.magento2.qa.store/-/pipelines/new?ref=main&var[PLUGIN_REF]=fix/ZES-99/my-thing
```

## Which version is on the store

Open the last `deploy-dev` job in the store's repository:

<https://gitlab.com/zip-au/plugins/zip.magento2.qa.store/-/jobs>

*(project `zip.magento2.qa.store` → left sidebar **Build → Jobs**, then the most
recent `deploy-dev`)*

Its first two lines say what went out:

```
deploying image tag : 26b7c65a-master-c7f7b8d6
plugin              : master@c7f7b8d6
```

The last part is the exact commit of this repository that is running.

## Checking the module actually works

The store provisions a product for exactly this:

- category **Zip QA** in the top menu → <https://zip-magento2-qa-store.dev.au.edge.zip.co/zip-qa.html>
- product **Zip QA Test Product**, SKU `ZIP-QA-001`, $100

Add it to the cart and go through checkout with an Australian address. At the
payment step **"Zip now, pay later"** appears with its acceptance mark, and
Continue redirects to `sandbox.zip.co/checkout?co=…` — a real checkout session
created through the Zip API with the store's sandbox key. Completing the
purchase from there needs Zip sandbox consumer credentials.

## Two things that waste an afternoon

**To change the branch, start a new pipeline.** Re-running an old `deploy-dev`
looks like it should work and does not: it quietly reuses the branch it was given
the first time. Always deploy from the pipeline that built the version you want.

**If the play button is refused,** you need *Developer* access to
`zip.magento2.qa.store`. The job runs as you, not as some service account.
