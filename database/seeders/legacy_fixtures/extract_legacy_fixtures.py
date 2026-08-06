#!/usr/bin/env python3
"""
`old_data_maktab.sql` (eski Yii2 MaktabGID bazasi, phpMyAdmin export) dan
Legacy* seederlar (backend/database/seeders/Legacy*.php) o'qiydigan toza JSON
fixture fayllarni chiqaradi.

Nega alohida skript kerak (PHP seeder ichida to'g'ridan-to'g'ri SQL faylni
o'qish o'rniga): `old_data_maktab.sql` qatorlarida ichma-ich JSON (masalan
`name` ustuni o'zi `{"ru":"...","uzl":"..."}` ko'rinishida), qochirilgan
tirnoqlar va ko'p qatorli HTML matn bor — buni PHP tomonida ishonchli
tokenlashtirish xavfli. Shu sababli bir martalik, tekshirilgan Python skript
orqali toza JSON qatorlarga aylantiriladi; seederlar shu JSON'larni
`json_decode(file_get_contents(...))` bilan o'qiydi.

Ishlatish (yangi backup kelsa qayta generatsiya qilish uchun):
    python3 database/seeders/legacy_fixtures/extract_legacy_fixtures.py \
        [/path/to/old_data_maktab.sql]

Natija: shu papkaga (legacy_fixtures/) <table>.json fayllar yoziladi.
"""

import json
import os
import re
import sys

DEFAULT_SQL_PATH = os.path.join(os.path.dirname(__file__), '..', '..', '..', 'old_data_maktab.sql')

TABLES = [
    'region', 'district', 'telegram_category', 'telegram_object',
    'telegram_object_photo', 'telegram_object_employee', 'telegram_object_comment',
    'telegram_object_rate', 'profession_to_object', 'vocations',
    'vocation_applications', 'advertisement', 'post', 'user', 'user_profile',
]


def parse_value_token(tok):
    tok = tok.strip()
    if tok == 'NULL':
        return None
    if tok.startswith("'") and tok.endswith("'"):
        inner = tok[1:-1]
        out = []
        i = 0
        mapping = {'n': '\n', 'r': '\r', 't': '\t', '\\': '\\', "'": "'", '"': '"', '0': '\0'}
        while i < len(inner):
            c = inner[i]
            if c == '\\' and i + 1 < len(inner):
                nxt = inner[i + 1]
                out.append(mapping.get(nxt, nxt))
                i += 2
                continue
            if c == "'" and i + 1 < len(inner) and inner[i + 1] == "'":
                out.append("'")
                i += 2
                continue
            out.append(c)
            i += 1
        return ''.join(out)
    try:
        return float(tok) if '.' in tok else int(tok)
    except ValueError:
        return tok


def split_row(row_content):
    """Bitta qator matnini (tashqi qavslar ichi) top-level vergul bo'yicha bo'ladi."""
    fields, buf, in_str, i, n = [], [], False, 0, len(row_content)
    while i < n:
        c = row_content[i]
        if in_str:
            buf.append(c)
            if c == '\\' and i + 1 < n:
                buf.append(row_content[i + 1])
                i += 2
                continue
            if c == "'":
                if i + 1 < n and row_content[i + 1] == "'":
                    buf.append("'")
                    i += 2
                    continue
                in_str = False
            i += 1
            continue
        if c == "'":
            in_str = True
            buf.append(c)
            i += 1
            continue
        if c == ',':
            fields.append(''.join(buf))
            buf = []
            i += 1
            continue
        buf.append(c)
        i += 1
    if buf:
        fields.append(''.join(buf))
    return [parse_value_token(f) for f in fields]


def parse_values_blob(values_blob):
    """Bitta INSERT statementning VALUES(...),(...),... qismini qatorlarga ajratadi."""
    rows, depth, in_str, start, i, n = [], 0, False, None, 0, len(values_blob)
    while i < n:
        c = values_blob[i]
        if in_str:
            if c == '\\' and i + 1 < n:
                i += 2
                continue
            if c == "'":
                if i + 1 < n and values_blob[i + 1] == "'":
                    i += 2
                    continue
                in_str = False
            i += 1
            continue
        if c == "'":
            in_str = True
            i += 1
            continue
        if c == '(':
            if depth == 0:
                start = i + 1
            depth += 1
            i += 1
            continue
        if c == ')':
            depth -= 1
            if depth == 0:
                rows.append(split_row(values_blob[start:i]))
            i += 1
            continue
        i += 1
    return rows


def parse_table(content, table):
    m = re.search(r"INSERT INTO `%s` \(([^)]*)\) VALUES" % re.escape(table), content)
    if not m:
        return []
    cols = [c.strip().strip('`') for c in m.group(1).split(',')]

    blobs = re.findall(r"INSERT INTO `%s` \([^)]*\) VALUES\s*(.*?);\n" % re.escape(table), content, re.DOTALL)
    all_rows = []
    for blob in blobs:
        all_rows.extend(parse_values_blob(blob))

    result = []
    for r in all_rows:
        if len(r) != len(cols):
            print(f"WARN: {table} row field count mismatch: {len(r)} vs {len(cols)} cols", file=sys.stderr)
            continue
        result.append(dict(zip(cols, r)))
    return result


def main():
    sql_path = sys.argv[1] if len(sys.argv) > 1 else DEFAULT_SQL_PATH
    with open(sql_path, 'r', encoding='utf-8', errors='replace') as f:
        content = f.read()

    out_dir = os.path.dirname(__file__)
    summary = {}
    for table in TABLES:
        rows = parse_table(content, table)
        summary[table] = len(rows)
        with open(os.path.join(out_dir, f'{table}.json'), 'w', encoding='utf-8') as f:
            json.dump(rows, f, ensure_ascii=False)

    print(json.dumps(summary, indent=2))


if __name__ == '__main__':
    main()
