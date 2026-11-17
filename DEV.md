# MAObjects S developer notes

## Paths

Paths below are relative to the theme root.

| File | Purpose |
| --- | --- |
| [view/common/resource-values.phtml](view/common/resource-values.phtml) | Chooses the view based on the item's resource class. |
| [view/common/work-resource-values.phtml](view/common/work-resource-values.phtml) | Work metadata and transmission previews. |
| [view/common/witness-resource-values.phtml](view/common/witness-resource-values.phtml) | Full Manuscript and Printed Book metadata. |
| [view/common/resource-values-list.phtml](view/common/resource-values-list.phtml) | Standard metadata output, including the fallback. |
| [asset/sass/_maobjects.scss](asset/sass/_maobjects.scss) | Custom styling, including mobile rules. |

## Adding or moving metadata

**New properties still appear automatically under “Further information.”**
The named sections use explicit field lists, so a new property needs a code change only if it should appear in a particular section.

- For Manuscript and Printed Book sections, edit `$witnessSections` in `witness-resource-values.phtml`. Move or add a `term => label` entry there. Empty fields and sections are hidden automatically.
- For the short Work facts, edit `$workFields` in `work-resource-values.phtml`.  Add newly rendered Work properties to `$handledWorkTerms` too, so they do not repeat under “Further information.” Only mark properties as handled when the custom view actually renders them.
- Transmission previews show only **Titles and text** and **Creation and publication**. Their field list is `$witnessPreviewSections` in the Work view. Change the full record and preview lists separately when needed.

## Linked records

The links come from Omeka metadata; item IDs are not hardcoded in the views.

| Relationship | Property on the source record |
| --- | --- |
| Work → Manuscript | `yido:manuscripts` |
| Work → Printed Book | `yido:printedEditions` |
| Genizah → Printed Book | `yido:content` |

Manuscript and Printed Book pages follow the incoming Work relationship and show a **Work** link at the end of **Titles and text**. Printed Books also show the linked Genizah title inside the **Holdings and access** grid. Its full metadata stays on the Genizah's own page. Incoming links use public records, with an API lookup fallback when Omeka's `subjectValues()` is empty.
