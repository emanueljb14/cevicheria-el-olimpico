<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ingresar | Cevichería El Olímpico</title>

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
            --danger: #c0392b;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            color: var(--ink);
            background: var(--navy-dark);
            font-family: 'DM Sans', sans-serif;
        }

        .login-page {
            display: grid;
            min-height: 100vh;
            grid-template-columns: minmax(390px, 1.05fr) minmax(500px, .95fr);
        }

        .visual-panel {
            position: relative;
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
            padding: 42px clamp(35px, 5vw, 75px);
            color: white;
            isolation: isolate;
            background:
                linear-gradient(155deg, rgba(3,27,39,.96) 0%, rgba(3,27,39,.70) 55%, rgba(0,167,167,.38) 100%),
                url('https://images.unsplash.com/photo-1535399831218-d5bd36d1a6b3?auto=format&fit=crop&w=1600&q=88') center/cover;
        }

        .visual-panel::after {
            content: '';
            position: absolute;
            right: -130px;
            bottom: -130px;
            z-index: -1;
            width: 380px;
            height: 380px;
            border: 1px solid rgba(246,196,83,.28);
            border-radius: 50%;
            box-shadow: 0 0 0 45px rgba(246,196,83,.035), 0 0 0 90px rgba(246,196,83,.025);
        }

        .brand {
            display: inline-flex;
            width: fit-content;
            align-items: center;
            gap: 12px;
            color: white;
            text-decoration: none;
        }

        .brand-icon {
            display: grid;
            width: 50px;
            height: 50px;
            place-items: center;
            border-radius: 50%;
            color: var(--navy);
            background: var(--gold);
            font-size: 21px;
        }

        .brand strong { display: block; font: 700 23px/1 'Playfair Display', serif; }
        .brand small { display: block; margin-top: 5px; font-size: 9px; letter-spacing: 2.7px; opacity: .78; }

        .visual-copy { max-width: 620px; padding-block: 75px; }
        .visual-tag { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 22px; padding: 8px 14px; border: 1px solid rgba(255,255,255,.22); border-radius: 999px; background: rgba(255,255,255,.09); font-size: 11px; font-weight: 700; letter-spacing: .1em; }
        .visual-tag i { color: var(--gold); }
        .visual-copy h1 { max-width: 590px; margin-bottom: 22px; font: 800 clamp(44px,6vw,76px)/1 'Playfair Display', serif; letter-spacing: -.04em; }
        .visual-copy h1 span { color: var(--gold); }
        .visual-copy p { max-width: 520px; color: #d4e2e6; font-size: 17px; line-height: 1.75; }

        .visual-footer { display: flex; align-items: center; justify-content: space-between; gap: 20px; color: rgba(255,255,255,.62); font-size: 12px; }
        .fresh-note { display: flex; align-items: center; gap: 8px; }
        .fresh-note i { color: var(--gold); }

        .form-panel {
            display: grid;
            min-height: 100vh;
            place-items: center;
            padding: 50px clamp(30px, 6vw, 90px);
            background:
                radial-gradient(circle at 90% 10%, rgba(0,167,167,.10), transparent 28%),
                var(--paper);
        }

        .form-wrapper { width: min(100%, 470px); }
        .mobile-brand { display: none; margin-bottom: 45px; color: var(--navy); }
        .form-heading { margin-bottom: 31px; }
        .form-heading .kicker { color: var(--sea); font-size: 11px; font-weight: 800; letter-spacing: .17em; text-transform: uppercase; }
        .form-heading h2 { margin: 10px 0 9px; color: var(--navy); font: 700 clamp(35px,4vw,48px)/1.05 'Playfair Display', serif; }
        .form-heading p { color: var(--muted); line-height: 1.6; }

        .status {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            padding: 13px 15px;
            border: 1px solid #a6e5d0;
            border-radius: 12px;
            color: #08764f;
            background: #edfff8;
            font-size: 13px;
        }

        .field { margin-bottom: 19px; }
        .field-top { display: flex; justify-content: space-between; gap: 15px; margin-bottom: 8px; }
        .field label { color: var(--navy); font-size: 13px; font-weight: 700; }
        .forgot { color: var(--sea-dark); font-size: 12px; font-weight: 700; text-decoration: none; }
        .forgot:hover { text-decoration: underline; }

        .input-shell { position: relative; }
        .input-shell > i:first-child { position: absolute; top: 50%; left: 16px; color: #8aa0a7; transform: translateY(-50%); pointer-events: none; }
        .input-shell input {
            width: 100%;
            height: 52px;
            padding: 0 48px 0 45px;
            border: 1px solid #cfdddf;
            border-radius: 13px;
            outline: none;
            color: var(--ink);
            background: white;
            font: 500 14px 'DM Sans', sans-serif;
            transition: border-color .2s, box-shadow .2s;
        }
        .input-shell input:focus { border-color: var(--sea); box-shadow: 0 0 0 4px rgba(0,167,167,.11); }
        .input-shell input.has-error { border-color: var(--danger); }
        .toggle-password { position: absolute; top: 50%; right: 10px; display: grid; width: 34px; height: 34px; place-items: center; border: 0; color: #82949a; background: transparent; cursor: pointer; transform: translateY(-50%); }
        .field-error { display: block; margin-top: 7px; color: var(--danger); font-size: 12px; }

        .form-options { display: flex; align-items: center; justify-content: space-between; gap: 15px; margin: 4px 0 23px; }
        .remember { display: inline-flex; align-items: center; gap: 9px; color: var(--muted); font-size: 13px; cursor: pointer; }
        .remember input { width: 17px; height: 17px; accent-color: var(--sea); }

        .submit-button {
            display: flex;
            width: 100%;
            height: 53px;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border: 0;
            border-radius: 999px;
            color: var(--navy);
            background: var(--gold);
            box-shadow: 0 12px 25px rgba(246,196,83,.25);
            font: 800 14px 'DM Sans', sans-serif;
            cursor: pointer;
            transition: transform .2s, background .2s;
        }
        .submit-button:hover { background: var(--gold-dark); transform: translateY(-2px); }

        .register-text { margin-top: 25px; color: var(--muted); font-size: 13px; text-align: center; }
        .register-text a { color: var(--sea-dark); font-weight: 800; text-decoration: none; }
        .register-text a:hover { text-decoration: underline; }
        .back-home { display: inline-flex; align-items: center; gap: 8px; margin-top: 35px; color: var(--muted); font-size: 12px; text-decoration: none; }
        .back-home:hover { color: var(--navy); }

        @media (max-width: 920px) {
            .login-page { grid-template-columns: 1fr; }
            .visual-panel { display: none; }
            .form-panel { min-height: 100vh; padding-block: 38px; }
            .mobile-brand { display: inline-flex; }
        }

        @media (max-width: 480px) {
            .form-panel { padding-inline: 21px; }
            .form-heading { margin-bottom: 26px; }
            .form-options { align-items: flex-start; flex-direction: column; }
        }
    </style>
</head>
<body>
    <main class="login-page">
        <section class="visual-panel" aria-label="Presentación de El Olímpico">
            <a class="brand" href="{{ route('welcome') }}">
                <span class="brand-icon"><i class="fa-solid fa-fish-fins"></i></span>
                <span><strong>EL OLÍMPICO</strong><small>CEVICHERÍA PERUANA</small></span>
            </a>

            <div class="visual-copy">
                <span class="visual-tag"><i class="fa-solid fa-lemon"></i> SABOR QUE NACE DEL MAR</span>
                <h1>Bienvenido a <span>nuestra mesa.</span></h1>
                <p>Ingresa para administrar pedidos, productos, mesas e inventario desde un solo lugar.</p>
            </div>

            <div class="visual-footer">
                <span>© {{ date('Y') }} El Olímpico</span>
                <span class="fresh-note"><i class="fa-solid fa-fish"></i> Frescura preparada al momento</span>
            </div>
        </section>

        <section class="form-panel">
            <div class="form-wrapper">
                <a class="brand mobile-brand" href="{{ route('welcome') }}">
                    <span class="brand-icon"><i class="fa-solid fa-fish-fins"></i></span>
                    <span><strong>EL OLÍMPICO</strong><small>CEVICHERÍA PERUANA</small></span>
                </a>

                <div class="form-heading">
                    <span class="kicker">Panel administrativo</span>
                    <h2>Iniciar sesión</h2>
                    <p>Ingresa tus datos para continuar con la gestión del restaurante.</p>
                </div>

                @if (session('status'))
                    <div class="status"><i class="fa-solid fa-circle-check"></i><span>{{ session('status') }}</span></div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="field">
                        <div class="field-top"><label for="email">Correo electrónico</label></div>
                        <div class="input-shell">
                            <i class="fa-regular fa-envelope"></i>
                            <input class="{{ $errors->has('email') ? 'has-error' : '' }}" id="email" name="email" type="email" value="{{ old('email') }}" placeholder="nombre@correo.com" autocomplete="username" required autofocus>
                        </div>
                        @error('email')<span class="field-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>@enderror
                    </div>

                    <div class="field">
                        <div class="field-top">
                            <label for="password">Contraseña</label>
                            @if (Route::has('password.request'))
                                <a class="forgot" href="{{ route('password.request') }}">¿La olvidaste?</a>
                            @endif
                        </div>
                        <div class="input-shell">
                            <i class="fa-solid fa-lock"></i>
                            <input class="{{ $errors->has('password') ? 'has-error' : '' }}" id="password" name="password" type="password" placeholder="Ingresa tu contraseña" autocomplete="current-password" required>
                            <button class="toggle-password" id="togglePassword" type="button" aria-label="Mostrar contraseña"><i class="fa-regular fa-eye"></i></button>
                        </div>
                        @error('password')<span class="field-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>@enderror
                    </div>

                    <div class="form-options">
                        <label class="remember" for="remember">
                            <input id="remember" name="remember" type="checkbox" {{ old('remember') ? 'checked' : '' }}>
                            Mantener mi sesión iniciada
                        </label>
                    </div>

                    <button class="submit-button" type="submit">
                        Ingresar al panel <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </form>

                @if (Route::has('register'))
                    <p class="register-text">¿Todavía no tienes una cuenta? <a href="{{ route('register') }}">Crear cuenta</a></p>
                @endif

                <a class="back-home" href="{{ route('welcome') }}"><i class="fa-solid fa-arrow-left"></i> Volver a la página principal</a>
            </div>
        </section>
    </main>

    <script>
        const toggle = document.getElementById('togglePassword');
        const password = document.getElementById('password');

        toggle.addEventListener('click', () => {
            const hidden = password.type === 'password';
            password.type = hidden ? 'text' : 'password';
            toggle.innerHTML = `<i class="fa-regular fa-eye${hidden ? '-slash' : ''}"></i>`;
            toggle.setAttribute('aria-label', hidden ? 'Ocultar contraseña' : 'Mostrar contraseña');
        });
    </script>
</body>
</html>