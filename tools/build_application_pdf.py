#!/usr/bin/env python3
"""Prepare the public Cleveland Renter rental application PDF.

The inherited source packet is already a fillable AcroForm (91 fields), so this
script does not build a form -- it corrects and prunes the one that exists:

  1. Drops page 6, the Screening Services Inc. tenant release. That page belongs
     to a different screening vendor than the one named on page 1, and it is the
     only page asking an applicant to write down an SSN.
  2. Removes the Social Security Number and Driver's License fields from both
     the applicant and Additional Occupants sections, clearing the blank rules
     and annotating each label with a short note. TransUnion SmartMove collects
     identity data from the applicant directly, so this business never takes
     custody of it.
  3. Fixes the fee, which reads $50 on the form but $75 on the instructions page
     and on the website.
  4. Repairs a line that renders as mojibake ("7KHDSDUWPHQWaddress...") because
     the source uses a non-embedded TimesNewRoman with identity encoding, and
     clears a stray tofu glyph after the word "cat".

Usage:  python3 tools/build_application_pdf.py

Developer script only. Nothing on the live site depends on it -- it exists so
the shipped PDF can be regenerated rather than hand-edited.
"""

from io import BytesIO
from pathlib import Path

from pypdf import PdfReader, PdfWriter
from pypdf.generic import (
    ArrayObject,
    ByteStringObject,
    ContentStream,
    NameObject,
    NumberObject,
)
from reportlab.lib.colors import Color, white
from reportlab.pdfgen import canvas

ROOT = Path(__file__).resolve().parent.parent
SOURCE = ROOT / "tools" / "source" / "rental-application-2026-source.pdf"
OUTPUT = ROOT / "assets" / "docs" / "cleveland-renter-rental-application-2026.pdf"

PAGE_W, PAGE_H = 612.0, 792.0
DROP_PAGE_INDEX = 5  # page 6, the Screening Services Inc. release

# Identity fields we decline to collect. Names are the source document's own.
REMOVE_FIELDS = {
    "SSN",
    "Drive License",
    "DL state",
    "roommate SSN",
    "Roommate DL No",
    "Roommate DL State",
}

LABEL_NAVY = Color(0.098, 0.212, 0.376)
NOTE_GRAY = Color(0.35, 0.35, 0.35)

# The source's own "Social Security Number:" / "Drive License Number:" labels are
# left in place and these notes are appended after them. The labels live inside
# Form XObjects and cannot be redacted from the text layer as cheaply as page
# content, so covering them would leave a duplicate in copy/paste and screen
# readers. Keeping them reads correctly both on screen and in the text layer.
NOTE_SSN = "not collected on this form."
NOTE_DL_APPLICANT = (
    "not collected on this form — the screening service verifies your identity directly."
)
NOTE_DL_OCCUPANT = (
    "not collected on this form — the screening service verifies each occupant's "
    "identity directly."
)


def flip(y_top: float) -> float:
    """Convert a top-origin y (as pdftotext reports) into PDF user space."""
    return PAGE_H - y_top


# ---------------------------------------------------------------------------
# Correction overlay
# ---------------------------------------------------------------------------


def _erase(c, x0, y_top0, x1, y_top1):
    c.setFillColor(white)
    c.rect(x0, flip(y_top1), x1 - x0, y_top1 - y_top0, stroke=0, fill=1)


def _note(c, x, y_top_baseline, text):
    c.setFillColor(NOTE_GRAY)
    c.setFont("Helvetica-Oblique", 7.6)
    c.drawString(x, flip(y_top_baseline), text)


def build_overlay() -> PdfReader:
    """Five blank pages carrying only the corrections for pages 4 and 5."""
    buf = BytesIO()
    c = canvas.Canvas(buf, pagesize=(PAGE_W, PAGE_H))

    # --- pages 1-3: nothing to correct ---
    for _ in range(3):
        c.showPage()

    # --- page 4: fee, SSN row, driver's licence row ---
    _erase(c, 159.0, 755.5, 176.0, 770.5)
    c.setFillColor(Color(0, 0, 0))
    c.setFont("Helvetica-Oblique", 8.5)
    c.drawString(159.8, flip(766.8), "$75")

    _erase(c, 192.0, 142.0, 341.0, 164.0)
    _note(c, 194.0, 157.0, NOTE_SSN)

    _erase(c, 187.0, 176.0, 578.0, 198.0)
    _note(c, 189.0, 191.0, NOTE_DL_APPLICANT)
    c.showPage()

    # --- page 5: occupant SSN/DL rows, mojibake line, stray glyph ---
    _erase(c, 192.0, 354.0, 341.0, 376.0)
    _note(c, 194.0, 369.0, NOTE_SSN)

    _erase(c, 187.0, 388.0, 578.0, 410.0)
    _note(c, 189.0, 403.0, NOTE_DL_OCCUPANT)

    _erase(c, 68.0, 643.0, 290.0, 666.0)
    c.setFillColor(LABEL_NAVY)
    c.setFont("Times-Roman", 11.5)
    c.drawString(70.1, flip(660.0), "The apartment address you want to apply for")

    # A tofu glyph renders immediately after "cat" and overlaps the final "t",
    # so clear the whole word and redraw it rather than trimming around it.
    _erase(c, 552.0, 674.0, 584.0, 692.0)
    c.setFillColor(LABEL_NAVY)
    c.setFont("Times-Roman", 12.5)
    c.drawString(554.7, flip(688.0), "cat")
    c.showPage()

    c.save()
    buf.seek(0)
    return PdfReader(buf)


# ---------------------------------------------------------------------------
# Form surgery
# ---------------------------------------------------------------------------


def blank_stale_fee(writer) -> bool:
    """Remove the stale "$50" from page 4's text layer.

    The white patch drawn in the overlay hides it on screen, but the glyphs stay
    in the content stream, so copy/paste, search and screen readers would still
    report the wrong fee. The run lives in a subsetted CID font that has no "7"
    glyph, so the number cannot simply be re-encoded. Instead the three glyphs
    are replaced with spaces and the width difference is given back as a kerning
    adjustment, which keeps every following glyph on the line exactly in place.
    The overlay supplies the visible "$75".
    """
    # Codes from the font's ToUnicode CMap: '$'=0x03a8, '5'=0x03f1, '0'=0x03ec.
    target = b"\x03\xa8\x03\xf1\x03\xec"
    space = b"\x00\x03"
    # Widths from the descendant font's /W array, in 1/1000 em.
    delta = (507 + 507 + 507) - (226 * 3)  # 843

    page = writer.pages[3]
    cs = ContentStream(page.get_contents(), writer)

    for op_index, (operands, op) in enumerate(cs.operations):
        if op not in (b"Tj", b"TJ"):
            continue
        parts = list(operands[0]) if op == b"TJ" else [operands[0]]
        for idx, part in enumerate(parts):
            raw = getattr(part, "original_bytes", None)
            if raw is None or target not in raw:
                continue
            at = raw.index(target)
            head = ByteStringObject(raw[:at] + space * 3)
            tail = ByteStringObject(raw[at + len(target):])
            new_parts = parts[:idx] + [head, NumberObject(-delta), tail] + parts[idx + 1:]
            # A Tj becomes a TJ now that it carries a kerning adjustment.
            cs.operations[op_index] = ([ArrayObject(new_parts)], b"TJ")
            page.replace_contents(cs)
            return True
    return False


def annots_of(page):
    annots = page.get("/Annots")
    if annots is None:
        return None
    return annots.get_object()


def strip_removed_widgets(writer) -> int:
    """Delete widget annotations belonging to the declined identity fields."""
    removed = 0
    for page in writer.pages:
        annots = annots_of(page)
        if annots is None:
            continue
        kept = ArrayObject()
        for ref in annots:
            obj = ref.get_object()
            name = obj.get("/T")
            if name is None and "/Parent" in obj:
                name = obj["/Parent"].get_object().get("/T")
            if name is not None and str(name) in REMOVE_FIELDS:
                removed += 1
                continue
            kept.append(ref)
        page[NameObject("/Annots")] = kept
    return removed


def prune_orphan_fields(writer) -> int:
    """Drop /Fields entries whose widgets no longer live on any surviving page."""
    acro = writer._root_object.get("/AcroForm")
    if acro is None:
        return 0
    acro = acro.get_object()

    alive = set()
    for page in writer.pages:
        annots = annots_of(page)
        if annots is None:
            continue
        for ref in annots:
            alive.add(ref.idnum)

    kept = ArrayObject()
    dropped = 0
    for ref in acro["/Fields"]:
        obj = ref.get_object()
        kids = obj.get("/Kids")
        if kids is not None:
            if any(k.idnum in alive for k in kids.get_object()):
                kept.append(ref)
            else:
                dropped += 1
        elif ref.idnum in alive:
            kept.append(ref)
        else:
            dropped += 1

    acro[NameObject("/Fields")] = kept
    return dropped


def main():
    if not SOURCE.exists():
        raise SystemExit(f"Source PDF not found: {SOURCE}")

    writer = PdfWriter(clone_from=str(SOURCE))

    overlay = build_overlay()
    for i in range(len(overlay.pages)):
        writer.pages[i].merge_page(overlay.pages[i])

    if hasattr(writer, "remove_page"):
        writer.remove_page(DROP_PAGE_INDEX)
    else:  # pragma: no cover - older pypdf
        kids = writer._pages.get_object()["/Kids"]
        del kids[DROP_PAGE_INDEX]
        writer._pages.get_object()[NameObject("/Count")] = len(kids)

    if not blank_stale_fee(writer):
        raise SystemExit("could not find the stale $50 run on page 4")

    removed = strip_removed_widgets(writer)
    dropped = prune_orphan_fields(writer)

    writer.add_metadata(
        {
            "/Title": "Cleveland Renter — Rental Application",
            "/Author": "Cleveland Renter",
            "/Subject": "Fillable rental application",
        }
    )

    OUTPUT.parent.mkdir(parents=True, exist_ok=True)
    with open(OUTPUT, "wb") as fh:
        writer.write(fh)

    remaining = len(writer._root_object["/AcroForm"].get_object()["/Fields"])
    print(f"Wrote {OUTPUT.relative_to(ROOT)}")
    print(f"  pages          : {len(writer.pages)}")
    print(f"  widgets removed: {removed} (SSN / driver's licence)")
    print(f"  fields dropped : {dropped} (orphaned by removing page 6)")
    print(f"  fields kept    : {remaining}")


if __name__ == "__main__":
    main()
