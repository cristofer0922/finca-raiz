/* FincaRaízPro - app.js */
document.addEventListener('DOMContentLoaded', () => {

    // Loader
    setTimeout(() => document.getElementById('page-loader')?.classList.add('hide'), 900);

    // AOS
    if (window.AOS) AOS.init({duration: 900, once: true, offset: 80});

    // Navbar scroll
    const nav = document.getElementById('mainNav');
    const onScroll = () => {
        if (window.scrollY > 60) nav?.classList.add('scrolled');
        else nav?.classList.remove('scrolled');
        const btt = document.getElementById('back-to-top');
        if (window.scrollY > 400) btt?.classList.add('show'); else btt?.classList.remove('show');
    };
    window.addEventListener('scroll', onScroll);
    onScroll();

    // Parallax hero
    const heroBg = document.querySelector('.hero-bg');
    if (heroBg) window.addEventListener('scroll', () => {
        heroBg.style.transform = `translateY(${window.scrollY * 0.35}px) scale(1.15)`;
    });

    // Back to top
    document.getElementById('back-to-top')?.addEventListener('click', e => {
        e.preventDefault(); window.scrollTo({top: 0, behavior: 'smooth'});
    });

    // Reveal observer
    const reveals = document.querySelectorAll('.reveal');
    if (reveals.length) {
        const io = new IntersectionObserver(es => es.forEach(e => e.isIntersecting && e.target.classList.add('show')), {threshold: .15});
        reveals.forEach(r => io.observe(r));
    }

    // Counters animados
    document.querySelectorAll('.stat-num').forEach(el => {
        const target = +el.dataset.count || 0;
        const obs = new IntersectionObserver(entries => entries.forEach(e => {
            if (e.isIntersecting) {
                let cur = 0;
                const step = Math.max(1, target / 60);
                const t = setInterval(() => {
                    cur += step;
                    if (cur >= target) { cur = target; clearInterval(t); }
                    el.textContent = Math.floor(cur).toLocaleString('es-CO');
                }, 30);
                obs.unobserve(el);
            }
        }));
        obs.observe(el);
    });

    // Swiper testimonios
    if (window.Swiper && document.querySelector('.swiper-testimonios')) {
        new Swiper('.swiper-testimonios', {
            slidesPerView: 1, spaceBetween: 30, loop: true, autoplay: {delay: 4500},
            pagination: {el: '.swiper-pagination', clickable: true},
            breakpoints: {768: {slidesPerView: 2}, 992: {slidesPerView: 3}}
        });
    }
    if (window.Swiper && document.querySelector('.swiper-destacadas')) {
        new Swiper('.swiper-destacadas', {
            slidesPerView: 1, spaceBetween: 25, loop: true, autoplay: {delay: 3500},
            navigation: {nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev'},
            breakpoints: {768: {slidesPerView: 2}, 992: {slidesPerView: 3}}
        });
    }

    // Flash alerts via Swal
    document.querySelectorAll('.alert-flash').forEach(a => {
        const type = a.dataset.type === 'success' ? 'success' : 'error';
        if (window.Swal) Swal.fire({
            toast: true, position: 'top-end', icon: type,
            title: a.dataset.msg, showConfirmButton: false, timer: 3500, timerProgressBar: true
        });
    });

    // Favoritos (localStorage)
    document.querySelectorAll('.property-fav').forEach(btn => {
        const id = btn.dataset.id;
        let favs = JSON.parse(localStorage.getItem('favs') || '[]');
        if (favs.includes(id)) btn.classList.add('active');
        btn.addEventListener('click', e => {
            e.preventDefault();
            favs = JSON.parse(localStorage.getItem('favs') || '[]');
            if (favs.includes(id)) { favs = favs.filter(x => x !== id); btn.classList.remove('active'); }
            else { favs.push(id); btn.classList.add('active');
                Swal.fire({toast:true,position:'top-end',icon:'success',title:'Agregado a favoritos',timer:1500,showConfirmButton:false});
            }
            localStorage.setItem('favs', JSON.stringify(favs));
        });
    });

    // Búsqueda en tiempo real (filtros)
    const buscador = document.getElementById('buscador-live');
    if (buscador) {
        buscador.addEventListener('input', e => {
            const q = e.target.value.toLowerCase();
            document.querySelectorAll('.property-card-wrap').forEach(c => {
                const txt = c.textContent.toLowerCase();
                c.style.display = txt.includes(q) ? '' : 'none';
            });
        });
    }

    // Chat widget
    const chatToggle = document.getElementById('chat-toggle');
    const chatBox = document.getElementById('chat-box');
    chatToggle?.addEventListener('click', () => chatBox.classList.toggle('open'));
    const chatSend = document.getElementById('chat-send');
    const chatInput = document.getElementById('chat-text');
    const sendMsg = () => {
        const v = chatInput.value.trim();
        if (!v) return;
        const body = chatBox.querySelector('.chat-body');
        body.insertAdjacentHTML('beforeend', `<div class="chat-msg user">${v}</div>`);
        chatInput.value = '';
        body.scrollTop = body.scrollHeight;
        setTimeout(() => {
            body.insertAdjacentHTML('beforeend', `<div class="chat-msg bot">Gracias por escribirnos. Un asesor se contactará pronto. 🏠</div>`);
            body.scrollTop = body.scrollHeight;
        }, 800);
    };
    chatSend?.addEventListener('click', sendMsg);
    chatInput?.addEventListener('keypress', e => e.key === 'Enter' && sendMsg());

    // Galería propiedad
    document.querySelectorAll('.gallery-thumbs img').forEach(img => {
        img.addEventListener('click', () => {
            document.querySelector('.gallery-main img').src = img.src;
            document.querySelectorAll('.gallery-thumbs img').forEach(i => i.classList.remove('active'));
            img.classList.add('active');
        });
    });

    // Admin sidebar toggle
    document.getElementById('sidebar-toggle')?.addEventListener('click', () =>
        document.querySelector('.admin-sidebar')?.classList.toggle('open'));

    // Confirmaciones eliminar
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', e => {
            e.preventDefault();
            Swal.fire({
                title: '¿Estás seguro?', text: 'Esta acción no se puede deshacer',
                icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#c9a14a', cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar'
            }).then(r => r.isConfirmed && btn.closest('form').submit());
        });
    });

    console.log('%c🏛️ FincaRaízPro - Premium Real Estate', 'color:#c9a14a;font-size:16px;font-weight:bold');
});
