(function(){
  const themeToggle=document.querySelector('[data-theme-toggle]');
  function setTheme(theme){
    document.documentElement.dataset.theme=theme;
    localStorage.setItem('vm_theme',theme);
    if(themeToggle)themeToggle.setAttribute('aria-pressed',theme==='dark'?'true':'false');
  }
  setTheme(localStorage.getItem('vm_theme')||document.documentElement.dataset.theme||'light');
  if(themeToggle){
    themeToggle.addEventListener('click',()=>{
      setTheme(document.documentElement.dataset.theme==='dark'?'light':'dark');
    });
  }

  const filterRoot=document.querySelector('[data-resource-filters]');
  if(filterRoot){
    const cards=[...document.querySelectorAll('[data-resource-card]')];
    const groups=[...document.querySelectorAll('[data-country-group]')];
    const count=document.querySelector('[data-results-count]');
    const controls={
      search:filterRoot.querySelector('[data-filter-search]'),
      country:filterRoot.querySelector('[data-filter-country]'),
      visa:filterRoot.querySelector('[data-filter-visa]'),
      type:filterRoot.querySelector('[data-filter-type]')
    };
    function norm(v){return (v||'').toLowerCase().trim();}
    function visaMatches(cardVisa, selectedVisa){
      if(!selectedVisa)return true;
      if(cardVisa===selectedVisa)return true;
      const routes={
        'tourist visa':['tourist','visitor','visit','transit'],
        'student visa':['student','study'],
        'work visa':['work','employment','worker'],
        'business visa':['business'],
        'medical visa':['medical','health','treatment']
      };
      return (routes[selectedVisa]||[]).some(term=>cardVisa.includes(term));
    }
    function apply(){
      const q=norm(controls.search.value), c=norm(controls.country.value), v=norm(controls.visa.value), t=norm(controls.type.value);
      let shown=0;
      cards.forEach(card=>{
        const hay=norm([card.dataset.country,card.dataset.visa,card.dataset.title,card.dataset.source,card.dataset.category].join(' '));
        const ok=(!q||hay.includes(q))&&(!c||norm(card.dataset.country)===c)&&visaMatches(norm(card.dataset.visa),v)&&(!t||norm(card.dataset.status)===t||norm(card.dataset.category).includes(t.replace('official_','').replace('_page','')));
        card.hidden=!ok;if(ok)shown++;
      });
      // Show/hide country groups based on whether they contain any visible cards
      groups.forEach(group=>{
        const visibleCards=group.querySelectorAll('[data-resource-card]:not([hidden])');
        group.hidden=visibleCards.length===0;
      });
      if(count)count.textContent=shown;
    }
    Object.values(controls).forEach(control=>control&&control.addEventListener('input',apply));
  }

  document.querySelectorAll('[data-country-details]').forEach(details=>{
    details.addEventListener('toggle',()=>{
      if(!details.open)return;
      document.querySelectorAll('[data-country-details]').forEach(other=>{
        if(other!==details)other.open=false;
      });
    });
  });

  document.querySelectorAll('[data-visa-hub-details]').forEach(details=>{
    details.addEventListener('toggle',()=>{
      if(!details.open)return;
      document.querySelectorAll('[data-visa-hub-details]').forEach(other=>{
        if(other!==details)other.open=false;
      });
    });
  });

  document.querySelectorAll('[data-card-href]').forEach(card=>{
    card.addEventListener('click',e=>{
      if(e.target.closest('a,button,input,select,textarea'))return;
      window.location.href=card.dataset.cardHref;
    });
  });

  const countryResourceFilter=document.querySelector('[data-country-resource-filter]');
  if(countryResourceFilter){
    const buttons=[...countryResourceFilter.querySelectorAll('[data-country-resource-route]')];
    const cards=[...document.querySelectorAll('[data-country-resource-grid] [data-resource-card]')];
    const count=document.querySelector('[data-country-resource-count]');
    const routeTerms={
      tourist:['tourist','visitor','visit','transit','eta','trv','short-stay'],
      student:['student','study','school','education','admission'],
      work:['work','employment','employee','worker','skilled','permit'],
      business:['business','commerce','meeting','conference','professional'],
      medical:['medical','treatment','health','hospital','patient']
    };
    const textFor=card=>[card.dataset.visa,card.dataset.title,card.dataset.category,card.textContent].join(' ').toLowerCase();
    function applyCountryResourceFilter(route){
      let shown=0;
      cards.forEach(card=>{
        const hay=textFor(card);
        const ok=!route || (routeTerms[route]||[]).some(term=>hay.includes(term));
        card.hidden=!ok;
        if(ok)shown++;
      });
      buttons.forEach(button=>button.classList.toggle('active',button.dataset.countryResourceRoute===route));
      if(count)count.textContent=`${shown} resource${shown===1?'':'s'} shown.`;
    }
    buttons.forEach(button=>button.addEventListener('click',()=>applyCountryResourceFilter(button.dataset.countryResourceRoute||'')));
  }

  // Mobile hamburger menu
  const menuToggle=document.querySelector('[data-menu-toggle]');
  const siteNav=document.querySelector('[data-site-nav]');
  if(menuToggle&&siteNav){
    menuToggle.addEventListener('click',()=>{
      const open=!siteNav.classList.contains('open');
      siteNav.classList.toggle('open',open);
      menuToggle.setAttribute('aria-expanded',open?'true':'false');
    });
    document.addEventListener('click',e=>{
      if(!menuToggle.contains(e.target)&&!siteNav.contains(e.target)){
        siteNav.classList.remove('open');
        menuToggle.setAttribute('aria-expanded','false');
      }
    });
  }

  // Back-to-top button
  const backToTop=document.querySelector('[data-back-to-top]');
  if(backToTop){
    const toggle=()=>backToTop.classList.toggle('visible',window.scrollY>400);
    window.addEventListener('scroll',toggle,{passive:true});
    toggle();
    backToTop.addEventListener('click',()=>window.scrollTo({top:0,behavior:'smooth'}));
  }

  // Smooth scroll for anchor links
  document.querySelectorAll('a[href^="#"]').forEach(a=>{
    a.addEventListener('click',e=>{
      const target=document.querySelector(a.getAttribute('href'));
      if(target){e.preventDefault();target.scrollIntoView({behavior:'smooth',block:'start'});}
    });
  });

  document.querySelectorAll('[data-print-target]').forEach(button=>{
    button.addEventListener('click',()=>window.print());
  });

  document.querySelectorAll('[data-download-letter]').forEach(button=>{
    button.addEventListener('click',()=>{
      const preview=document.querySelector('.letter-preview');
      if(!preview)return;
      const text=preview.innerText.replace(/\n{3,}/g,'\n\n').trim()+"\n\nGenerated by VisaMenged as a starter draft. Edit with your real evidence before submission.";
      const blob=new Blob([text],{type:'text/plain;charset=utf-8'});
      const url=URL.createObjectURL(blob);
      const a=document.createElement('a');
      a.href=url;
      a.download='visamenged-letter-draft.txt';
      document.body.appendChild(a);
      a.click();
      a.remove();
      URL.revokeObjectURL(url);
    });
  });

  document.querySelectorAll('.drop-upload-zone').forEach(zone => {
    const input = zone.querySelector('input[type="file"]');
    const summary = zone.querySelector('[data-file-summary]');
    if (!input || !summary) { return; }
    const updateSummary = () => {
      const files = [...(input.files || [])];
      if (!files.length) {
        summary.textContent = 'No files selected';
        return;
      }
      const names = files.slice(0, 3).map(file => file.name);
      summary.textContent = files.length > 3 ? `${files.length} files: ${names.join(', ')}...` : names.join(', ');
    };
    ['dragenter', 'dragover'].forEach(eventName => {
      zone.addEventListener(eventName, event => {
        event.preventDefault();
        zone.classList.add('drag-over');
      });
    });
    ['dragleave', 'drop'].forEach(eventName => {
      zone.addEventListener(eventName, event => {
        event.preventDefault();
        zone.classList.remove('drag-over');
      });
    });
    zone.addEventListener('drop', event => {
      const files = event.dataTransfer && event.dataTransfer.files;
      if (!files || !files.length) { return; }
      input.files = files;
      input.dispatchEvent(new Event('change', { bubbles: true }));
    });
    input.addEventListener('change', updateSummary);
  });

  const paddleConfig = window.VM_PADDLE || null;
  if (paddleConfig && window.Paddle && paddleConfig.token) {
    if ((paddleConfig.env || '').toLowerCase() === 'sandbox' && Paddle.Environment) {
      Paddle.Environment.set('sandbox');
    }
    Paddle.Initialize({ token: paddleConfig.token });
    document.querySelectorAll('[data-paddle-checkout]').forEach(el => {
      el.addEventListener('click', (event) => {
        const priceId = el.getAttribute('data-paddle-price-id');
        if (!priceId) { return; }
        event.preventDefault();
        Paddle.Checkout.open({
          items: [{ priceId, quantity: 1 }],
          customData: {
            product: el.getAttribute('data-paddle-product') || 'service',
            source_page: window.location.pathname
          }
        });
      });
    });
  }
})();
