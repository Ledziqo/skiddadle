(function(){
  const key='visamenged_basket_v1';
  const panel=document.querySelector('[data-basket-panel]');
  const itemsEl=document.querySelector('[data-basket-items]');
  const countEl=document.querySelector('[data-basket-count]');
  const i18n=window.VM_I18N||{};
  const disclaimer='VisaMenged is independent guidance. Always verify final requirements on the official embassy, government, VFS, TLS, or visa-center website before applying. VisaMenged does not guarantee approval.';
  const read=()=>{try{return JSON.parse(localStorage.getItem(key)||'[]')}catch(e){return[]}};
  const write=items=>{localStorage.setItem(key,JSON.stringify(items));render()};
  const esc=s=>(s||'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
  function grouped(items){
    return items.reduce((acc,item)=>{const type=item.type||'Saved item';(acc[type] ||= []).push(item);return acc},{});
  }
  function textExport(){
    const items=read(), groups=grouped(items);
    const lines=[i18n.exportTitle||'VisaMenged - Saved list',i18n.tagline||'The clear path to your visa application.',(i18n.generated||'Generated')+': '+new Date().toLocaleString(),''];
    if(!items.length) lines.push(i18n.noItems||'No saved items yet.');
    Object.keys(groups).forEach(type=>{
      lines.push(type.toUpperCase());
      groups[type].forEach((i,n)=>{lines.push(`${n+1}. ${i.title||'Saved item'}`); if(i.meta)lines.push(`   ${i.meta}`); if(i.url)lines.push(`   ${i.url}`);});
      lines.push('');
    });
    lines.push(disclaimer);
    return lines.join('\n');
  }
  function render(){
    const items=read();
    if(countEl)countEl.textContent=items.length;
    if(!itemsEl)return;
    itemsEl.innerHTML=items.length?'':`<p class="muted">${esc(i18n.noItems||'No saved items yet.')}</p>`;
    if(items.length){
      const types=items.map(i=>(i.type||'').toLowerCase()).join(' ');
      const titles=items.map(i=>(i.title||'').toLowerCase()).join(' ');
      const advice=[];
      if(!types.includes('official')&&!titles.includes('form')&&!titles.includes('portal')) advice.push('Add at least one official form, portal or requirement page.');
      if(!types.includes('template')&&!titles.includes('letter')) advice.push('Add a support template if your purpose, sponsor, employer or invitation story needs explaining.');
      if(!types.includes('service')) advice.push('Run a file score or choose a service if you already know the weak point.');
      if(titles.includes('refusal')) advice.push('Add previous refusal recovery help before reapplying.');
      const plan=document.createElement('div');
      plan.className='basket-plan';
      plan.innerHTML=`<strong>Visa File Plan</strong><ul>${(advice.length?advice:['Good start. Print this list and verify each official source before applying.']).map(a=>`<li>${esc(a)}</li>`).join('')}</ul>`;
      itemsEl.appendChild(plan);
    }
    items.forEach((item,index)=>{
      const div=document.createElement('div');div.className='basket-item';
      div.innerHTML='<strong></strong><span class="muted"></span><div class="actions"><a class="button ghost" target="_blank" rel="noopener"></a><button class="button secondary" type="button"></button></div>';
      div.querySelector('strong').textContent=item.title||'Saved item';
      div.querySelector('span').textContent=[item.type,item.meta].filter(Boolean).join(' - ');
      div.querySelector('a').href=item.url||'#';
      div.querySelector('a').textContent=i18n.open||'Open';
      div.querySelector('button').textContent=i18n.remove||'Remove';
      div.querySelector('button').addEventListener('click',()=>{const next=read();next.splice(index,1);write(next)});
      itemsEl.appendChild(div);
    });
  }
  function printList(){
    const items=read(), groups=grouped(items);
    const body=Object.keys(groups).map(type=>`<h2>${esc(type)}</h2><ol>${groups[type].map(i=>`<li><strong>${esc(i.title)}</strong>${i.meta?`<br><span>${esc(i.meta)}</span>`:''}${i.url?`<br><small>${esc(i.url)}</small>`:''}</li>`).join('')}</ol>`).join('') || `<p>${esc(i18n.noItems||'No saved items yet.')}</p>`;
    const win=window.open('','_blank','width=900,height=700');
    if(!win){window.print();return;}
    win.document.write(`<!doctype html><html><head><title>${esc(i18n.exportTitle||'VisaMenged - Saved list')}</title><style>body{font-family:Arial,sans-serif;color:#172033;margin:36px;line-height:1.45}h1{color:#103B5B}h2{border-bottom:1px solid #E5E0D6;padding-bottom:6px;color:#0E8F72}li{margin:0 0 12px}.promise{font-weight:700;color:#103B5B}.disclaimer{margin-top:28px;padding:14px;background:#FFF7E6;border-left:4px solid #C9A227}small{color:#555}</style></head><body><h1>${esc(i18n.exportTitle||'VisaMenged - Saved list')}</h1><p class="promise">${esc(i18n.tagline||'The clear path to your visa application.')}</p><p>${esc(i18n.generated||'Generated')}: ${esc(new Date().toLocaleString())}</p>${body}<p class="disclaimer">${esc(disclaimer)}</p><script>window.onload=()=>window.print()</script></body></html>`);
    win.document.close();
  }
  document.addEventListener('click',event=>{
    const add=event.target.closest('[data-add-basket]');
    if(add){const item=JSON.parse(add.getAttribute('data-add-basket')||'{}');const items=read();items.push({...item,addedAt:new Date().toISOString()});write(items);panel&&panel.classList.add('open')}
  });
  document.querySelector('[data-basket-open]')?.addEventListener('click',()=>panel&&panel.classList.add('open'));
  document.querySelector('[data-basket-close]')?.addEventListener('click',()=>panel&&panel.classList.remove('open'));
  document.querySelector('[data-basket-clear]')?.addEventListener('click',()=>write([]));
  document.querySelector('[data-basket-print]')?.addEventListener('click',printList);
  document.querySelector('[data-basket-download]')?.addEventListener('click',()=>{
    const blob=new Blob([textExport()],{type:'text/plain'}), a=document.createElement('a');
    a.href=URL.createObjectURL(blob);a.download='visamenged-my-visa-file.txt';a.click();URL.revokeObjectURL(a.href);
  });
  render();
})();
