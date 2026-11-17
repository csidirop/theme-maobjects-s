# MAObjects S developer notes

## Paths

Paths below are relative to the theme root.

| File | Purpose |
| --- | --- |
| [view/common/resource-values.phtml](view/common/resource-values.phtml) | Chooses the view based on the item's resource class. |
| [view/common/work-resource-values.phtml](view/common/work-resource-values.phtml) | Work metadata and transmission previews. |
| [view/common/witness-resource-values.phtml](view/common/witness-resource-values.phtml) | Full metadata for Manuscripts, Printed Books, and Single Works within either type. |
| [view/common/resource-values-list.phtml](view/common/resource-values-list.phtml) | Standard metadata output, including the fallback. |
| [view/common/resource-parent-links.phtml](view/common/resource-parent-links.phtml) | Parent navigation beneath the item class badge. |
| [view/common/resource-page-block-layout/linked-resources.phtml](view/common/resource-page-block-layout/linked-resources.phtml) | Hides the generic incoming-links block for the five custom item classes. |
| [view/omeka/site/item/show.phtml](view/omeka/site/item/show.phtml) | Main item title, Yiddish title, and resource-class badge. |
| [asset/sass/_maobjects.scss](asset/sass/_maobjects.scss) | Custom styling, including mobile rules. |

## Adding or moving metadata

**New properties still appear automatically under “Further information.”**
The named sections use explicit field lists, so a new property needs a code change only if it should appear in a particular section.

- For Manuscript and Printed Book sections, edit `$witnessSections` in `witness-resource-values.phtml`. Move or add a `term => label` entry there. Empty fields and sections are hidden automatically.
- For the short Work facts, edit `$workFields` in `work-resource-values.phtml`.  Add newly rendered Work properties to `$handledWorkTerms` too, so they do not repeat under “Further information.” Only mark properties as handled when the custom view actually renders them.
- Transmission previews show only **Titles and Content** and **Creation and publication**. Their field list is `$witnessPreviewSections` in the Work view. Change the full record and preview lists separately when needed.

## Linked records

The links come from Omeka metadata; item IDs are not hardcoded in the views.

| Relationship | Property on the source record |
| --- | --- |
| Work → Manuscript | `yido:manuscripts` |
| Work → Printed Book | `yido:printedEditions` |
| Genizah → Printed Book | `yido:content` |
| Manuscript → Single Work in Manuscript | `yido:content` |
| Printed Book → Single Work in Printed Book | `yido:content` |
| Single Work → Containing manuscript or printed book | `yido:isPartOf` |
| Reprint → Earlier printed book | `yido:reprintOf` |

Manuscript and Printed Book pages show a **Work: …** link beneath the class badge. This follows incoming `yido:manuscripts` / `yido:printedEditions` from a Work. Single Works show **Contained in: …** there, resolving both the parent's `yido:content` and their own `yido:isPartOf`, with each parent shown once by item ID. Direct Work relationships on Single Works also appear there. Work pages have no parent navigation. Printed Books show the linked Genizah title inside the **Holdings and access** grid. Its full metadata stays on the Genizah's own page. Incoming links use public records, with an API lookup fallback when Omeka's `subjectValues()` is empty.

Always record Work relationships on the **Work**, using `yido:manuscripts` or `yido:printedEditions`. Both **Transmission** and the witness's **Work: …** navigation use those same relationships. A `yido:content` link pointing back to a Work is a metadata error to correct in Omeka; the theme does not infer a Work relationship from it.

Manuscript names appear under **Titles and Content**. Content values appear in a separate **Contents** section: linked texts open their full record, literal values remain plain text, and annotations such as page ranges are shown beside the entry. Incoming `yido:isPartOf` records also appear here, deduplicated by item ID against existing content links. Both Single Work classes use the shared metadata sections. Parent links appear in the heading; unusual or annotated `yido:isPartOf` values remain in **Further information**. Incoming `yido:reprintOf` records appear as **Reprints** under **Notes and bibliography**.

The generic **Linked resources** block is hidden for Work, Manuscript, Printed Book, and both Single Work classes. Other classes and item sets keep Omeka's normal block. When adding a new incoming relationship for these five classes, add it to their custom view too: incoming links do not use the **Further information** fallback. The site setting to exclude incoming resources outside the current site is respected.

Yiddish titles appear directly below the main item title, with right-to-left text direction. Work pages use `yido:workTitleHebrew`; Manuscripts, Printed Books, and their Single Works use `yido:shortTitleYiddish`. Empty titles are omitted; alternative Yiddish titles stay in **Titles and Content**.
