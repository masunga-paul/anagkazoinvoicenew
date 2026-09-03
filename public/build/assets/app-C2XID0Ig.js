function e(){let e=document.getElementById(`global-nav-loader`);!e&&document.body&&(e=document.createElement(`div`),e.id=`global-nav-loader`,e.className=`fixed inset-0 z-[9999] bg-white text-zinc-900 flex flex-col items-center justify-center opacity-0 pointer-events-none transition-opacity duration-200 select-none`,e.innerHTML=`
            <div id="global-nav-bar" class="fixed top-0 left-0 h-1 bg-gradient-to-r from-[#0a192f] via-[#1e3a8a] to-blue-500 z-50 transition-all duration-300 w-0 shadow-[0_0_10px_rgba(30,58,138,0.4)]"></div>
            <div id="global-nav-card" class="flex flex-col items-center text-center space-y-4 transform scale-95 transition-all duration-200 px-6">
                <div class="relative w-14 h-14 flex items-center justify-center">
                    <svg class="animate-spin w-14 h-14 text-[#1e3a8a]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-15" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                        <path class="opacity-95" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600 animate-ping"></span>
                    </div>
                </div>
                <div class="space-y-0.5">
                    <h3 class="text-sm font-bold tracking-wider text-zinc-900">Loading ....</h3>
                    <p class="text-[11px] text-zinc-400 font-medium tracking-wide">Please wait</p>
                </div>
            </div>
        `,document.body.appendChild(e))}var t=null;function n(){e();let n=document.getElementById(`global-nav-loader`),r=document.getElementById(`global-nav-bar`),i=document.getElementById(`global-nav-card`);!n||!r||(n.classList.remove(`opacity-0`,`pointer-events-none`),n.classList.add(`opacity-100`,`pointer-events-auto`),i&&i.classList.remove(`scale-95`),r.style.width=`30%`,clearInterval(t),t=setInterval(()=>{let e=parseInt(r.style.width)||30;e<85&&(r.style.width=e+Math.floor(Math.random()*12+4)+`%`)},150))}function r(){let e=document.getElementById(`global-nav-loader`),n=document.getElementById(`global-nav-bar`),r=document.getElementById(`global-nav-card`);!e||!n||(clearInterval(t),n.style.width=`100%`,setTimeout(()=>{e.classList.remove(`opacity-100`,`pointer-events-auto`),e.classList.add(`opacity-0`,`pointer-events-none`),r&&r.classList.add(`scale-95`),setTimeout(()=>{n.style.width=`0%`},250)},200))}document.addEventListener(`DOMContentLoaded`,()=>{e()}),document.addEventListener(`livewire:navigating`,()=>{n()}),document.addEventListener(`livewire:navigated`,()=>{r()}),document.addEventListener(`click`,e=>{let t=e.target.closest(`a`);!t||!t.href||t.target===`_blank`||t.hasAttribute(`download`)||t.href.startsWith(`javascript:`)||t.href.startsWith(`#`)||t.href.startsWith(window.location.origin)&&n()}),window.addEventListener(`beforeunload`,()=>{n()});