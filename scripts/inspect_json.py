import json
with open('data/visa_menged_research_seed_may_2026.json', 'r', encoding='utf-8') as f:
    data = json.load(f)
print(f'Total countries: {len(data)}')
for c in data:
    types = [vt['visa_type'] for vt in c.get('visa_types', [])]
    print(f"  {c['country']}: {types}")