<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Crear cuenta | Cevichería El Olímpico</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --navy:#062b3d; --navy-dark:#031b27; --sea:#00a7a7;
            --sea-dark:#078080; --gold:#f6c453; --gold-dark:#e0b043;
            --paper:#fffdf8; --ink:#18242b; --muted:#68757d; --danger:#c0392b;
        }
        * { box-sizing:border-box; margin:0; padding:0; }
        body { min-height:100vh; color:var(--ink); background:var(--navy-dark); font-family:'DM Sans',sans-serif; }
        .register-page { display:grid; min-height:100vh; grid-template-columns:minmax(390px,1.02fr) minmax(540px,.98fr); }

        .visual-panel {
            position:relative; display:flex; min-height:100vh; flex-direction:column;
            justify-content:space-between; overflow:hidden; padding:42px clamp(35px,5vw,75px);
            color:white; isolation:isolate;
            background:linear-gradient(155deg,rgba(3,27,39,.97),rgba(3,27,39,.72) 55%,rgba(0,167,167,.36)),
            url('https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=1600&q=88') center/cover;
        }
        .visual-panel::after { content:''; position:absolute; right:-130px; bottom:-130px; z-index:-1; width:380px; height:380px; border:1px solid rgba(246,196,83,.28); border-radius:50%; box-shadow:0 0 0 45px rgba(246,196,83,.035),0 0 0 90px rgba(246,196,83,.025); }
        .brand { display:inline-flex; width:fit-content; align-items:center; gap:12px; color:white; text-decoration:none; }
        .brand-icon { display:grid; width:50px; height:50px; place-items:center; border-radius:50%; color:var(--navy); background:var(--gold); font-size:21px; }
        .brand strong { display:block; font:700 23px/1 'Playfair Display',serif; }
        .brand small { display:block; margin-top:5px; font-size:9px; letter-spacing:2.7px; opacity:.78; }
        .visual-copy { max-width:620px; padding-block:60px; }
        .visual-tag { display:inline-flex; align-items:center; gap:8px; margin-bottom:22px; padding:8px 14px; border:1px solid rgba(255,255,255,.22); border-radius:999px; background:rgba(255,255,255,.09); font-size:11px; font-weight:700; letter-spacing:.1em; }
        .visual-tag i { color:var(--gold); }
        .visual-copy h1 { max-width:600px; margin-bottom:22px; font:800 clamp(44px,6vw,74px)/1 'Playfair Display',serif; letter-spacing:-.04em; }
        .visual-copy h1 span { color:var(--gold); }
        .visual-copy p { max-width:530px; color:#d4e2e6; font-size:17px; line-height:1.75; }
        .benefits { display:grid; gap:12px; margin-top:28px; }
        .benefit { display:flex; align-items:center; gap:11px; color:#e6eff1; font-size:13px; }
        .benefit i { display:grid; width:30px; height:30px; place-items:center; border-radius:50%; color:var(--navy); background:var(--gold); font-size:11px; }
        .visual-footer { display:flex; justify-content:space-between; gap:20px; color:rgba(255,255,255,.62); font-size:12px; }

        .form-panel { display:grid; min-height:100vh; place-items:center; padding:38px clamp(30px,5vw,78px); background:radial-gradient(circle at 90% 10%,rgba(0,167,167,.1),transparent 28%),var(--paper); }
        .form-wrapper { width:min(100%,490px); }
        .mobile-brand { display:none; margin-bottom:35px; color:var(--navy); }
        .form-heading { margin-bottom:25px; }
        .kicker { color:var(--sea); font-size:11px; font-weight:800; letter-spacing:.17em; text-transform:uppercase; }
        .form-heading h2 { margin:9px 0 8px; color:var(--navy); font:700 clamp(34px,4vw,47px)/1.05 'Playfair Display',serif; }
        .form-heading p { color:var(--muted); font-size:14px; line-height:1.6; }
        .field { margin-bottom:16px; }
        .field label { display:block; margin-bottom:7px; color:var(--navy); font-size:13px; font-weight:700; }
        .input-shell { position:relative; }
        .input-shell > i:first-child { position:absolute; top:50%; left:16px; color:#8aa0a7; transform:translateY(-50%); pointer-events:none; }
        .input-shell input { width:100%; height:50px; padding:0 47px 0 45px; border:1px solid #cfdddf; border-radius:13px; outline:none; color:var(--ink); background:white; font:500 14px 'DM Sans',sans-serif; transition:.2s; }
        .input-shell input:focus { border-color:var(--sea); box-shadow:0 0 0 4px rgba(0,167,167,.11); }
        .input-shell input.has-error { border-color:var(--danger); }
        .toggle-password { position:absolute; top:50%; right:9px; display:grid; width:34px; height:34px; place-items:center; border:0; color:#82949a; background:transparent; cursor:pointer; transform:translateY(-50%); }
        .field-error { display:block; margin-top:6px; color:var(--danger); font-size:12px; }
        .password-hint { display:block; margin-top:6px; color:#84949a; font-size:11px; }
        .terms { display:flex; align-items:flex-start; gap:9px; margin:5px 0 20px; color:var(--muted); font-size:12px; line-height:1.5; }
        .terms input { width:17px; height:17px; flex:0 0 17px; margin-top:1px; accent-color:var(--sea); }
        .terms a { color:var(--sea-dark); font-weight:700; }
        .submit-button { display:flex; width:100%; height:52px; align-items:center; justify-content:center; gap:10px; border:0; border-radius:999px; color:var(--navy); background:var(--gold); box-shadow:0 12px 25px rgba(246,196,83,.25); font:800 14px 'DM Sans',sans-serif; cursor:pointer; transition:.2s; }
        .submit-button:hover { background:var(--gold-dark); transform:translateY(-2px); }
        .login-text { margin-top:22px; color:var(--muted); font-size:13px; text-align:center; }
        .login-text a { color:var(--sea-dark); font-weight:800; text-decoration:none; }
        .login-text a:hover { text-decoration:underline; }
        .back-home { display:inline-flex; align-items:center; gap:8px; margin-top:27px; color:var(--muted); font-size:12px; text-decoration:none; }
        .back-home:hover { color:var(--navy); }

        @media(max-width:950px) { .register-page{grid-template-columns:1fr}.visual-panel{display:none}.form-panel{padding-block:35px}.mobile-brand{display:inline-flex} }
        @media(max-width:480px) { .form-panel{padding-inline:21px}.form-heading{margin-bottom:22px} }
    </style>
</head>
<body>
    <main class="register-page">
        <section class="visual-panel" aria-label="Presentación de El Olímpico">
            <a class="brand" href="{{ route('welcome') }}">
                <span class="brand-icon"><i class="fa-solid fa-fish-fins"></i></span>
                <span><strong>EL OLÍMPICO</strong><small>CEVICHERÍA PERUANA</small></span>
            </a>
            <div class="visual-copy">
                <span class="visual-tag"><i class="fa-solid fa-star"></i> ÚNETE A NUESTRA MESA</span>
                <h1>Crea tu cuenta y vive <span>la experiencia.</span></h1>
                <p>Regístrate para acceder de forma segura al sistema de gestión de la Cevichería El Olímpico.</p>
                <div class="benefits">
                    <div class="benefit"><i class="fa-solid fa-check"></i><span>Acceso seguro y personalizado</span></div>
                    <div class="benefit"><i class="fa-solid fa-check"></i><span>Gestión centralizada del restaurante</span></div>
                    <div class="benefit"><i class="fa-solid fa-check"></i><span>Información actualizada en tiempo real</span></div>
                </div>
            </div>
            <div class="visual-footer"><span>© {{ date('Y') }} El Olímpico</span><span>Tradición y sabor peruano</span></div>
        </section>

        <section class="form-panel">
            <div class="form-wrapper">
                <a class="brand mobile-brand" href="{{ route('welcome') }}">
                    <span class="brand-icon"><i class="fa-solid fa-fish-fins"></i></span>
                    <span><strong>EL OLÍMPICO</strong><small>CEVICHERÍA PERUANA</small></span>
                </a>

                <div class="form-heading">
                    <span class="kicker">Nuevo usuario</span>
                    <h2>Crear una cuenta</h2>
                    <p>Completa tus datos para comenzar. Todos los campos son obligatorios.</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="field">
                        <label for="name">Nombre completo</label>
                        <div class="input-shell">
                            <i class="fa-regular fa-user"></i>
                            <input class="{{ $errors->has('name') ? 'has-error' : '' }}" id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Escribe tu nombre" autocomplete="name" required autofocus>
                        </div>
                        @error('name')<span class="field-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>@enderror
                    </div>

                    <div class="field">
                        <label for="email">Correo electrónico</label>
                        <div class="input-shell">
                            <i class="fa-regular fa-envelope"></i>
                            <input class="{{ $errors->has('email') ? 'has-error' : '' }}" id="email" name="email" type="email" value="{{ old('email') }}" placeholder="nombre@correo.com" autocomplete="username" required>
                        </div>
                        @error('email')<span class="field-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>@enderror
                    </div>

                    <div class="field">
                        <label for="password">Contraseña</label>
                        <div class="input-shell">
                            <i class="fa-solid fa-lock"></i>
                            <input class="{{ $errors->has('password') ? 'has-error' : '' }}" id="password" name="password" type="password" placeholder="Mínimo 8 caracteres" autocomplete="new-password" minlength="8" required>
                            <button class="toggle-password" type="button" data-target="password" aria-label="Mostrar contraseña"><i class="fa-regular fa-eye"></i></button>
                        </div>
                        <small class="password-hint">Usa al menos 8 caracteres.</small>
                        @error('password')<span class="field-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>@enderror
                    </div>

                    <div class="field">
                        <label for="password_confirmation">Confirmar contraseña</label>
                        <div class="input-shell">
                            <i class="fa-solid fa-shield-halved"></i>
                            <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Repite tu contraseña" autocomplete="new-password" minlength="8" required>
                            <button class="toggle-password" type="button" data-target="password_confirmation" aria-label="Mostrar confirmación"><i class="fa-regular fa-eye"></i></button>
                        </div>
                    </div>

                    <label class="terms">
                        <input type="checkbox" required>
                        <span>Acepto los <a href="#">términos de uso</a> y la <a href="#">política de privacidad</a>.</span>
                    </label>

                    <button class="submit-button" type="submit">Crear mi cuenta <i class="fa-solid fa-arrow-right"></i></button>
                </form>

                <p class="login-text">¿Ya tienes una cuenta? <a href="{{ route('login') }}">Iniciar sesión</a></p>
                <a class="back-home" href="{{ route('welcome') }}"><i class="fa-solid fa-arrow-left"></i> Volver a la página principal</a>
            </div>
        </section>
    </main>

    <script>
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', () => {
                const input = document.getElementById(button.dataset.target);
                const hidden = input.type === 'password';
                input.type = hidden ? 'text' : 'password';
                button.innerHTML = `<i class="fa-regular fa-eye${hidden ? '-slash' : ''}"></i>`;
                button.setAttribute('aria-label', hidden ? 'Ocultar contraseña' : 'Mostrar contraseña');
            });
        });
    </script>
</body>
</html>