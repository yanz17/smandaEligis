<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <title>Login</title>
</head>
<body class="hero min-h-screen" style="background-image: url(/img/hero-bg.JPG)">
    <div class="hero-overlay"></div>
    <div class="flex justify-center items-center">
        <section class="flex flex-col justify-center items-center min-w-2xs px-8 pt-4 pb-6 gap-2 bg-black/75 rounded-4xl">
            <span>
                <a href="/" class="link-hover text-yellow-500 text-lg font-semibold">Kembali</a>
            </span>
            <h1 class="text-4xl font-bold text-white text-center mb-4">Login</h1>
            
            <form action="" method="post">
              <section class="flex flex-col justify-center items-center">
                <div class="mb-3">
                  <label class="input input-lg w-sm validator">
                      <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <g
                          stroke-linejoin="round"
                          stroke-linecap="round"
                          stroke-width="2.5"
                          fill="none"
                          stroke="currentColor"
                        >
                          <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                          <circle cx="12" cy="7" r="4"></circle>
                        </g>
                      </svg>
                      <input
                        type="input"
                        required
                        placeholder="Username"
                        pattern="[A-Za-z][A-Za-z0-9\-]*"
                        minlength="3"
                        maxlength="30"
                        title="Only letters, numbers or dash"
                      />
                  </label>
                  <p class="validator-hint hidden">
                  Must be 3 to 30 characters
                  <br />containing only letters, numbers or dash
                  </p>
                </div>

                {{-- Password --}}
                <div class="mb-4">
                    <label class="input input-lg w-sm validator">
                        <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                          <g
                            stroke-linejoin="round"
                            stroke-linecap="round"
                            stroke-width="2.5"
                            fill="none"
                            stroke="currentColor"
                          >
                            <path
                              d="M2.586 17.414A2 2 0 0 0 2 18.828V21a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1h1a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1h.172a2 2 0 0 0 1.414-.586l.814-.814a6.5 6.5 0 1 0-4-4z"
                            ></path>
                            <circle cx="16.5" cy="7.5" r=".5" fill="currentColor"></circle>
                          </g>
                        </svg>
                        <input
                          type="password"
                          required
                          placeholder="Password"
                          minlength="8"
                          pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                          title="Must be more than 8 characters, including number, lowercase letter, uppercase letter"
                        />
                    </label>
                    <p class="validator-hint hidden">
                    Must be more than 8 characters, including
                    <br />At least one number <br />At least one lowercase letter <br />At least one uppercase letter
                    </p>
                </div>

                <button class="btn btn-primary">Submit</button>
              </section>
              {{-- Username --}}

            </form>

        </section>
    </div>
    
</body>
</html>