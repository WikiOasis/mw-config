#!/usr/bin/env python3
"""
Creates empty extension.json/skin.json placeholders for every entry in
GlobalExtensions.php's wfLoadExtensions()/wfLoadSkins() calls, except
WikiOasisMagic (checked out for real, since its composer.json is merged
into MediaWiki core's dependencies).

ExtensionRegistry::queue() only calls filemtime() on these paths at
LocalSettings.php time; it doesn't parse them until the full Setup.php
bootstrap, which the smoke test never reaches. So an empty file is enough
to satisfy wfLoadExtensions()/wfLoadSkins() without cloning every extension.
"""
import re
import os
import sys

core = sys.argv[1]
content = open(f"{core}/config/GlobalExtensions.php").read()


def parse(block):
	items = []
	for line in block.splitlines():
		line = line.strip()
		if line.startswith('//') or not line:
			continue
		m = re.match(r"'([^']+)'", line)
		if m:
			items.append(m.group(1))
	return items


skins = parse(re.search(r"wfLoadSkins\(\s*\[(.*?)\]", content, re.S).group(1))
exts = parse(re.search(r"wfLoadExtensions\(\s*\[(.*?)\]", content, re.S).group(1))

for skin in skins:
	d = f"{core}/skins/{skin}"
	os.makedirs(d, exist_ok=True)
	if not os.path.exists(f"{d}/skin.json"):
		open(f"{d}/skin.json", "a").close()

for ext in exts:
	if ext == 'WikiOasisMagic':
		continue
	d = f"{core}/extensions/{ext}"
	os.makedirs(d, exist_ok=True)
	if not os.path.exists(f"{d}/extension.json"):
		open(f"{d}/extension.json", "a").close()
