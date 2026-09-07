<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>El Olímpico | Cevichería Peruana</title>
    <meta name="description" content="Cevichería El Olímpico: pescados frescos, recetas peruanas y el auténtico sabor del mar.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --navy: #062b3d;
            --navy-dark: #031b27;
            --sea: #00a7a7;
            --sea-dark: #078080;
            --gold: #f6c453;
            --gold-dark: #e0b043;
            --paper: #fffdf8;
            --mist: #f1f6f6;
            --ink: #18242b;
            --muted: #68757d;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body { overflow-x: hidden; color: var(--ink); background: var(--paper); font-family: 'DM Sans', sans-serif; }
        img { display: block; max-width: 100%; }
        a { color: inherit; }
        .container { width: min(1180px, calc(100% - 40px)); margin-inline: auto; }

        .site-header {
            position: fixed; inset: 0 0 auto; z-index: 1000;
            transition: background .25s, box-shadow .25s;
        }
        .site-header.scrolled { background: rgba(3,27,39,.96); box-shadow: 0 8px 30px rgba(0,0,0,.18); backdrop-filter: blur(14px); }
        .navbar { display: flex; min-height: 82px; align-items: center; justify-content: space-between; gap: 25px; }
        .logo { display: flex; align-items: center; gap: 11px; color: #fff; text-decoration: none; }
        .logo-mark { display: grid; width: 47px; height: 47px; place-items: center; border-radius: 50%; color: var(--navy); background: var(--gold); font-size: 20px; }
        .logo strong { display: block; font: 700 22px/1 'Playfair Display', serif; }
        .logo small { display: block; margin-top: 5px; font-size: 9px; letter-spacing: 2.5px; opacity: .76; }
        .nav-menu { display: flex; align-items: center; gap: 26px; }
        .nav-menu a { color: #fff; text-decoration: none; font-size: 14px; font-weight: 600; }
        .nav-menu a:hover { color: var(--gold); }
        .nav-login { padding: 9px 17px; border: 1px solid rgba(246,196,83,.8); border-radius: 999px; color: var(--gold) !important; }
        .nav-order { padding: 10px 18px; border-radius: 999px; color: var(--navy) !important; background: var(--gold); }
        .menu-button { display: none; border: 0; color: white; background: transparent; font-size: 25px; }

        .hero {
            position: relative; display: grid; min-height: 96vh; align-items: center; isolation: isolate;
            color: white; background:
                linear-gradient(90deg, rgba(3,27,39,.97) 0%, rgba(3,27,39,.78) 48%, rgba(3,27,39,.25) 100%),
                url('https://images.unsplash.com/photo-1535399831218-d5bd36d1a6b3?auto=format&fit=crop&w=2000&q=88') center/cover;
        }
        .hero::after { content:''; position:absolute; inset:auto 0 0; height:100px; z-index:-1; background:linear-gradient(to top,rgba(3,27,39,.36),transparent); }
        .hero-content { padding-block: 140px 95px; }
        .eyebrow { display: inline-flex; align-items: center; gap: 9px; margin-bottom: 23px; padding: 9px 15px; border: 1px solid rgba(255,255,255,.25); border-radius: 999px; background: rgba(255,255,255,.1); font-size: 12px; letter-spacing: .08em; backdrop-filter: blur(8px); }
        .eyebrow i { color: var(--gold); }
        .hero h1 { max-width: 800px; margin-bottom: 25px; font: 800 clamp(50px,8vw,94px)/.98 'Playfair Display',serif; letter-spacing: -.04em; }
        .hero h1 em { color: var(--gold); font-style: normal; }
        .hero-description { max-width: 590px; margin-bottom: 34px; color: #dce9ec; font-size: 18px; line-height: 1.75; }
        .hero-actions { display: flex; flex-wrap: wrap; gap: 13px; }
        .button { display: inline-flex; align-items: center; justify-content: center; gap: 10px; padding: 14px 23px; border: 0; border-radius: 999px; text-decoration: none; font-weight: 700; transition: transform .2s, background .2s; }
        .button:hover { transform: translateY(-3px); }
        .button-gold { color: var(--navy); background: var(--gold); }
        .button-gold:hover { background: var(--gold-dark); }
        .button-ghost { border: 1px solid rgba(255,255,255,.55); color: white; background: transparent; }
        .button-ghost:hover { color: var(--navy); background: white; }
        .hero-note { position:absolute; right:5vw; bottom:42px; display:flex; align-items:center; gap:13px; padding:14px 17px; border:1px solid rgba(255,255,255,.2); border-radius:16px; background:rgba(3,27,39,.68); backdrop-filter:blur(12px); }
        .hero-note i { color:var(--gold); font-size:22px; }
        .hero-note strong, .hero-note small { display:block; }
        .hero-note small { color:#bed0d6; }

        .trust-strip { position: relative; z-index: 3; display: grid; grid-template-columns: repeat(3,1fr); margin-top: -36px; overflow:hidden; border-radius: 19px; background:white; box-shadow:0 22px 60px rgba(3,27,39,.15); }
        .trust-item { display:flex; align-items:center; justify-content:center; gap:13px; padding:24px; border-right:1px solid #e6eeee; }
        .trust-item:last-child { border:0; }
        .trust-item i { display:grid; width:43px; height:43px; place-items:center; border-radius:50%; color:var(--sea); background:#e7f7f7; }
        .trust-item strong, .trust-item small { display:block; }
        .trust-item small { margin-top:3px; color:var(--muted); font-size:12px; }

        .section { padding: 100px 0; }
        .section-soft { background: var(--mist); }
        .section-heading { display:flex; align-items:end; justify-content:space-between; gap:30px; margin-bottom:45px; }
        .kicker { color:var(--sea); font-size:12px; font-weight:800; letter-spacing:.18em; text-transform:uppercase; }
        .section-heading h2, .story-copy h2 { max-width:650px; margin-top:10px; color:var(--navy); font:700 clamp(35px,5vw,55px)/1.08 'Playfair Display',serif; letter-spacing:-.025em; }
        .section-heading p { max-width:420px; color:var(--muted); line-height:1.7; }

        .menu-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:24px; }
        .dish-card { position:relative; overflow:hidden; border:1px solid #e3ebed; border-radius:22px; background:white; box-shadow:0 10px 35px rgba(3,27,39,.06); transition:.3s; }
        .dish-card:hover { transform:translateY(-8px); box-shadow:0 22px 45px rgba(3,27,39,.13); }
        .dish-image { position:relative; height:245px; overflow:hidden; background:#dce8ea; }
        .dish-image img { width:100%; height:100%; object-fit:cover; transition:transform .45s; }
        .dish-card:hover img { transform:scale(1.06); }
        .dish-label { position:absolute; top:15px; left:15px; padding:7px 11px; border-radius:999px; color:var(--navy); background:var(--gold); font-size:10px; font-weight:800; letter-spacing:.08em; }
        .dish-body { padding:22px; }
        .dish-body h3 { color:var(--navy); font:700 24px 'Playfair Display',serif; }
        .dish-body p { min-height:67px; margin:9px 0 17px; color:var(--muted); font-size:14px; line-height:1.6; }
        .dish-footer { display:flex; align-items:center; justify-content:space-between; gap:15px; }
        .dish-price { color:var(--sea-dark); font-size:20px; font-weight:800; }
        .dish-order { display:grid; width:40px; height:40px; place-items:center; border-radius:50%; color:white; background:var(--navy); text-decoration:none; }
        .dish-order:hover { background:var(--sea); }
        .empty-menu { grid-column:1/-1; padding:45px; border:1px dashed #bdd1d5; border-radius:20px; color:var(--muted); text-align:center; background:white; }

        .story { display:grid; grid-template-columns:1fr 1fr; align-items:center; gap:75px; }
        .story-images { position:relative; padding:0 45px 45px 0; }
        .story-main { width:100%; height:520px; object-fit:cover; border-radius:26px; }
        .story-detail { position:absolute; right:0; bottom:0; width:210px; height:210px; object-fit:cover; border:9px solid var(--paper); border-radius:22px; }
        .story-seal { position:absolute; top:25px; right:8px; display:grid; width:105px; height:105px; place-items:center; padding:12px; border-radius:50%; color:var(--navy); background:var(--gold); text-align:center; font-size:11px; font-weight:800; transform:rotate(7deg); }
        .story-copy p { margin-top:18px; color:var(--muted); line-height:1.8; }
        .features { display:grid; grid-template-columns:1fr 1fr; gap:15px; margin-top:28px; }
        .feature { display:flex; align-items:center; gap:11px; font-size:14px; font-weight:700; }
        .feature i { display:grid; width:39px; height:39px; place-items:center; border-radius:50%; color:var(--sea); background:#e7f7f7; }

        .visit { position:relative; overflow:hidden; padding:90px 0; color:white; background:var(--navy); }
        .visit::before { content:'OLÍMPICO'; position:absolute; right:-25px; top:-45px; color:rgba(255,255,255,.035); font:800 170px 'Playfair Display',serif; }
        .visit-grid { position:relative; display:grid; grid-template-columns:1.2fr .8fr; align-items:center; gap:60px; }
        .visit h2 { margin:10px 0 18px; font:700 clamp(38px,5vw,60px)/1.05 'Playfair Display',serif; }
        .visit p { max-width:620px; color:#c9dade; line-height:1.75; }
        .visit-actions { display:flex; flex-wrap:wrap; gap:12px; margin-top:28px; }
        .visit-card { padding:25px; border:1px solid rgba(255,255,255,.13); border-radius:20px; background:rgba(255,255,255,.07); backdrop-filter:blur(10px); }
        .visit-row { display:flex; gap:13px; padding:13px 0; border-bottom:1px solid rgba(255,255,255,.1); }
        .visit-row:last-child { border:0; }
        .visit-row i { width:22px; color:var(--gold); text-align:center; }
        .visit-row strong, .visit-row small { display:block; }
        .visit-row small { margin-top:4px; color:#b6cbd1; }

        footer { padding:55px 0 25px; color:#b1c5cb; background:var(--navy-dark); }
        .footer-grid { display:grid; grid-template-columns:1.5fr 1fr 1fr; gap:50px; }
        footer h3 { margin-bottom:15px; color:white; font:700 19px 'Playfair Display',serif; }
        footer p, footer a { color:#9eb5bc; font-size:14px; line-height:1.9; text-decoration:none; }
        footer a:hover { color:var(--gold); }
        .socials { display:flex; gap:9px; margin-top:17px; }
        .socials a { display:grid; width:39px; height:39px; place-items:center; border:1px solid #294551; border-radius:50%; }
        .copyright { margin-top:40px; padding-top:22px; border-top:1px solid #203b47; font-size:12px; text-align:center; }
        .whatsapp { position:fixed; right:22px; bottom:22px; z-index:900; display:grid; width:58px; height:58px; place-items:center; border-radius:50%; color:white; background:#25d366; text-decoration:none; font-size:28px; box-shadow:0 12px 30px rgba(0,0,0,.22); transition:transform .2s; }
        .whatsapp:hover { color:white; transform:scale(1.08); }

        @media (max-width: 900px) {
            .menu-button { display:block; }
            .nav-menu { position:absolute; top:82px; left:20px; right:20px; display:none; flex-direction:column; align-items:stretch; padding:22px; border:1px solid rgba(255,255,255,.12); border-radius:18px; background:rgba(3,27,39,.98); text-align:center; }
            .nav-menu.open { display:flex; }
            .hero-note { display:none; }
            .trust-strip { grid-template-columns:1fr; margin-top:20px; }
            .trust-item { justify-content:flex-start; border-right:0; border-bottom:1px solid #e6eeee; }
            .menu-grid { grid-template-columns:1fr 1fr; }
            .story, .visit-grid { grid-template-columns:1fr; }
            .section-heading { display:block; }
            .section-heading p { margin-top:18px; }
        }

        @media (max-width: 620px) {
            .container { width:min(100% - 28px,1180px); }
            .hero { min-height:90vh; }
            .hero-content { padding-block:125px 65px; }
            .hero-description { font-size:16px; }
            .button { width:100%; }
            .section { padding:75px 0; }
            .menu-grid, .features, .footer-grid { grid-template-columns:1fr; }
            .story-images { padding-right:22px; }
            .story-main { height:430px; }
            .story-detail { width:145px; height:145px; }
            .story-seal { right:-2px; width:88px; height:88px; }
        }
    </style>
</head>
<body>
    <header class="site-header" id="siteHeader">
        <nav class="navbar container" aria-label="Navegación principal">
            <a href="#inicio" class="logo">
                <span class="logo-mark"><i class="fa-solid fa-fish-fins"></i></span>
                <span><strong>EL OLÍMPICO</strong><small>CEVICHERÍA PERUANA</small></span>
            </a>

            <div class="nav-menu" id="navMenu">
                <a href="#carta">Nuestra carta</a>
                <a href="#historia">Nuestra historia</a>
                <a href="#visitanos">Visítanos</a>
                @auth
                    <a class="nav-login" href="{{ route('dashboard') }}"><i class="fa-solid fa-gauge-high"></i> Ir al panel</a>
                @else
                    <a class="nav-login" href="{{ route('login') }}"><i class="fa-regular fa-user"></i> Ingresar</a>
                @endauth
                <a class="nav-order" target="_blank" href="https://wa.me/51920555000?text={{ urlencode('Hola, deseo realizar un pedido en la Cevichería El Olímpico.') }}">Pedir ahora</a>
            </div>

            <button class="menu-button" id="menuButton" type="button" aria-label="Abrir menú" aria-expanded="false">
                <i class="fa-solid fa-bars"></i>
            </button>
        </nav>
    </header>

    <main>
        <section class="hero" id="inicio">
            <div class="hero-content container">
                <span class="eyebrow"><i class="fa-solid fa-lemon"></i> FRESCURA QUE SE PREPARA AL MOMENTO</span>
                <h1>Del mar a tu mesa, <em>con sabor peruano.</em></h1>
                <p class="hero-description">Ceviches, jaleas y platos marinos preparados con pescado fresco, limón peruano y esa sazón que convierte un almuerzo en un buen recuerdo.</p>
                <div class="hero-actions">
                    <a href="#carta" class="button button-gold">Descubrir la carta <i class="fa-solid fa-arrow-down"></i></a>
                    <a href="https://wa.me/51960860791?text={{ urlencode('Hola, quiero reservar una mesa en El Olímpico.') }}" target="_blank" class="button button-ghost"><i class="fa-brands fa-whatsapp"></i> Reservar una mesa</a>
                </div>
            </div>
            <div class="hero-note">
                <i class="fa-solid fa-clock"></i>
                <div><strong>Preparación al momento</strong><small>Porque lo fresco no se apura</small></div>
            </div>
        </section>

        <div class="trust-strip container">
            <div class="trust-item"><i class="fa-solid fa-fish"></i><div><strong>Pesca seleccionada</strong><small>Ingredientes elegidos cada día</small></div></div>
            <div class="trust-item"><i class="fa-solid fa-mortar-pestle"></i><div><strong>Recetas de la casa</strong><small>Sazón peruana con identidad</small></div></div>
            <div class="trust-item"><i class="fa-solid fa-people-group"></i><div><strong>Ambiente familiar</strong><small>Una mesa para compartir</small></div></div>
        </div>

        <section class="section section-soft" id="carta">
            <div class="container">
                <div class="section-heading">
                    <div><span class="kicker">Favoritos de la casa</span><h2>Platos que hablan por El Olímpico</h2></div>
                    <p>Una pequeña muestra de nuestra carta. Cada plato se prepara al momento para conservar su frescura y sabor.</p>
                </div>

                <div class="menu-grid">
                    @forelse (($productos ?? collect())->take(6) as $producto)
                        @php
                            $imagen = $producto->imagen
                                ? asset('storage/' . $producto->imagen)
                                : 'https://images.unsplash.com/photo-1535399831218-d5bd36d1a6b3?auto=format&fit=crop&w=900&q=85';
                        @endphp
                        <article class="dish-card">
                            <div class="dish-image">
                                <img src="{{ $imagen }}" alt="{{ $producto->nombre }}" loading="lazy">
                                <span class="dish-label">PREPARADO AL MOMENTO</span>
                            </div>
                            <div class="dish-body">
                                <h3>{{ $producto->nombre }}</h3>
                                <p>{{ $producto->descripcion ?: 'Preparado con ingredientes seleccionados y la sazón de nuestra casa.' }}</p>
                                <div class="dish-footer">
                                    <strong class="dish-price">S/ {{ number_format($producto->precio, 2) }}</strong>
                                    <a class="dish-order" target="_blank" href="https://wa.me/51920555000?text={{ urlencode('Hola, deseo pedir: ' . $producto->nombre) }}" aria-label="Pedir {{ $producto->nombre }}"><i class="fa-brands fa-whatsapp"></i></a>
                                </div>
                            </div>
                        </article>
                    @empty
                        @foreach ([
                            ['Ceviche Olímpico', 'Pescado fresco, leche de tigre de la casa, cebolla, choclo y camote.', '35.00', 'https://images.unsplash.com/photo-1535399831218-d5bd36d1a6b3?auto=format&fit=crop&w=900&q=85'],
                            ['Jalea de la Casa', 'Pescado y mariscos crocantes con yuca dorada y salsa criolla.', '40.00', 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=900&q=85'],
                            ['Arroz con Mariscos', 'Arroz jugoso, mariscos seleccionados y el aderezo especial de la casa.', '35.00', 'https://images.unsplash.com/photo-1559339352-11d035aa65de?auto=format&fit=crop&w=900&q=85']
                        ] as [$nombre, $descripcion, $precio, $imagen])
                            <article class="dish-card">
                                <div class="dish-image"><img src="{{ $imagen }}" alt="{{ $nombre }}" loading="lazy"><span class="dish-label">FAVORITO</span></div>
                                <div class="dish-body">
                                    <h3>{{ $nombre }}</h3><p>{{ $descripcion }}</p>
                                    <div class="dish-footer"><strong class="dish-price">S/ {{ $precio }}</strong><a class="dish-order" target="_blank" href="https://wa.me/51920555000?text={{ urlencode('Hola, deseo pedir: ' . $nombre) }}"><i class="fa-brands fa-whatsapp"></i></a></div>
                                </div>
                            </article>
                        @endforeach
                    @endforelse
                </div>
            </div>
        </section>

        <section class="section" id="historia">
            <div class="container story">
                <div class="story-images">
                    <img class="story-main" src="https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=1000&q=85" alt="Preparación de comida peruana" loading="lazy">
                    <img class="story-detail" src="https://images.unsplash.com/photo-1515003197210-e0cd71810b5f?auto=format&fit=crop&w=500&q=85" alt="Detalle de plato peruano" loading="lazy">
                    <span class="story-seal">SABOR<br>DE NUESTRA<br>CASA</span>
                </div>
                <div class="story-copy">
                    <span class="kicker">Nuestra manera de cocinar</span>
                    <h2>Una cevichería con sabor propio.</h2>
                    <p>En El Olímpico creemos que un buen plato marino comienza con ingredientes frescos y termina con una mesa compartida. Por eso trabajamos cada preparación al momento, cuidando el equilibrio entre limón, ají y sazón.</p>
                    <p>No queremos ser una cevichería más: queremos ser ese lugar al que vuelves cuando deseas comer rico, sentirte cómodo y compartir con los tuyos.</p>
                    <div class="features">
                        <div class="feature"><i class="fa-solid fa-lemon"></i><span>Limón peruano</span></div>
                        <div class="feature"><i class="fa-solid fa-pepper-hot"></i><span>Picante a tu gusto</span></div>
                        <div class="feature"><i class="fa-solid fa-kitchen-set"></i><span>Preparación diaria</span></div>
                        <div class="feature"><i class="fa-solid fa-heart"></i><span>Atención cercana</span></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="visit" id="visitanos">
            <div class="container visit-grid">
                <div>
                    <span class="kicker" style="color:var(--gold)">Reserva y pedidos</span>
                    <h2>Tu próxima mesa te está esperando.</h2>
                    <p>Escríbenos para reservar o consultar la disponibilidad de tus platos favoritos. Te responderemos directamente por WhatsApp.</p>
                    <div class="visit-actions">
                        <a class="button button-gold" target="_blank" href="https://wa.me/51960860791?text={{ urlencode('Hola, quiero reservar una mesa en El Olímpico.') }}"><i class="fa-brands fa-whatsapp"></i> Reservar ahora</a>
                        <a class="button button-ghost" href="tel:+51920555000"><i class="fa-solid fa-phone"></i> Llamar</a>
                    </div>
                </div>
                <div class="visit-card">
                    <div class="visit-row"><i class="fa-solid fa-location-dot"></i><div><strong>Encuéntranos</strong><small>Agrega aquí la dirección real del local</small></div></div>
                    <div class="visit-row"><i class="fa-regular fa-clock"></i><div><strong>Horario de atención</strong><small>Lunes a domingo · 10:00 a. m. – 8:00 p. m.</small></div></div>
                    <div class="visit-row"><i class="fa-solid fa-phone"></i><div><strong>Reservas y pedidos</strong><small>+51 960 860 791</small></div></div>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container footer-grid">
            <div><div class="logo"><span class="logo-mark"><i class="fa-solid fa-fish-fins"></i></span><span><strong>EL OLÍMPICO</strong><small>CEVICHERÍA PERUANA</small></span></div><p style="margin-top:18px;max-width:380px">Frescura, sazón y una mesa preparada para compartir el auténtico sabor marino del Perú.</p><div class="socials"><a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a><a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a><a href="#" aria-label="TikTok"><i class="fa-brands fa-tiktok"></i></a></div></div>
            <div><h3>Explora</h3><p><a href="#carta">Nuestra carta</a></p><p><a href="#historia">Nuestra historia</a></p><p><a href="#visitanos">Reservas</a></p></div>
            <div><h3>Información</h3><p><i class="fa-solid fa-location-dot"></i> Lima, Perú</p><p><i class="fa-solid fa-phone"></i> +51 960 860 791</p><p><i class="fa-regular fa-envelope"></i> contacto@elolimpico.pe</p></div>
        </div>
        <div class="container copyright">© {{ date('Y') }} Cevichería El Olímpico. Hecho con sabor peruano.</div>
    </footer>

    <a class="whatsapp" target="_blank" href="https://wa.me/51960860791?text={{ urlencode('Hola, deseo información sobre El Olímpico.') }}" aria-label="Contactar por WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>

    <script>
        const header = document.getElementById('siteHeader');
        const menu = document.getElementById('navMenu');
        const button = document.getElementById('menuButton');

        const updateHeader = () => header.classList.toggle('scrolled', window.scrollY > 30);
        updateHeader();
        window.addEventListener('scroll', updateHeader, { passive: true });

        button.addEventListener('click', () => {
            const open = menu.classList.toggle('open');
            button.setAttribute('aria-expanded', open);
            button.innerHTML = `<i class="fa-solid fa-${open ? 'xmark' : 'bars'}"></i>`;
        });

        menu.querySelectorAll('a').forEach(link => link.addEventListener('click', () => {
            menu.classList.remove('open');
            button.setAttribute('aria-expanded', 'false');
            button.innerHTML = '<i class="fa-solid fa-bars"></i>';
        }));
    </script>
</body>
</html>
