<section class="mb-5">
    <header class="mb-4">
        <h2 class="h4 text-color">
            {{ __('messages.profilepartialsupdateprofile.profileInfo') }}
        </h2>
        <p class="text-color small">
            {{ __('messages.profilepartialsupdateprofile.profileUpdateMessage') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="mb-3 text-color">
            <label for="name" class="form-label"> {{ __('messages.profilepartialsupdateprofile.username') }}</label>
            <input type="text" class="form-control" id="name" name="name"
                   value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @if ($errors->get('name'))
                <div class="text-danger small mt-1">
                    {{ $errors->first('name') }}
                </div>
            @endif
        </div>

        <div class="mb-3 text-color">
            <label for="first_name" class="form-label"> {{ __('messages.profilepartialsupdateprofile.firstName') }}</label>
            <input type="text" class="form-control" id="first_name" name="first_name"
                   value="{{ old('first_name', $user->first_name) }}" required autofocus autocomplete="first_name">
            @if ($errors->get('first_name'))
                <div class="text-danger small mt-1">
                    {{ $errors->first('first_name') }}
                </div>
            @endif
        </div>

        <div class="mb-3 text-color">
            <label for="last_name" class="form-label"> {{ __('messages.profilepartialsupdateprofile.lastName') }}</label>
            <input type="text" class="form-control" id="last_name" name="last_name"
                   value="{{ old('last_name', $user->last_name) }}" required autofocus autocomplete="last_name">
            @if ($errors->get('last_name'))
                <div class="text-danger small mt-1">
                    {{ $errors->first('last_name') }}
                </div>
            @endif
        </div>

        <div class="mb-3 text-color">
            <label for="email" class="form-label"> {{ __('messages.profilepartialsupdateprofile.email') }}</label>
            <input type="email" class="form-control" id="email" name="email"
                   value="{{ old('email', $user->email) }}" required autocomplete="username">
            @if ($errors->get('email'))
                <div class="text-danger small mt-1">
                    {{ $errors->first('email') }}
                </div>
            @endif

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 small text-color">
                    {{ __('messages.profilepartialsupdateprofile.verifyEmailError') }}

                    <button form="send-verification" class="btn btn-link p-0 align-baseline text-decoration-underline">
                        {{ __('messages.profilepartialsupdateprofile.sendVerifyAgain') }}
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <div class="text-success mt-1">
                            {{ __('messages.profilepartialsupdateprofile.sendVerifyAgainInfo') }}
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <div class="mb-3 text-color">
            <label for="description" class="form-label"> {{ __('messages.account.aboutMe') }}</label>
            <input type="text" class="form-control" id="description" name="description"
                   value="{{ old('description', $user->description) }}" required autofocus autocomplete="description">
            @if ($errors->get('descriptione'))
<<<<<<< HEAD
=======
                <div class="text-danger small mt-1">
                    {{ $errors->first('description') }}
                </div>
            @endif
        </div>

        <div class="mb-3 text-color">
            <label class="form-label">{{ __('messages.profilepartialsupdateprofile.avatar') }}</label>

            @if($user->avatar)
                <div class="mb-2">
                    @if(Auth::user()->photo_path)
                        <img src="{{ asset('storage/' . Auth::user()->photo_path) }}"
                             class="rounded-circle profile-pic" alt="{{ __('messages.profilepartialsupdateprofile.profilePhoto') }}" width="80" height="80">
                    @else
                        <img src="{{ asset('images/includes/default-avatar.png') }}"
                             class="rounded-circle profile-pic" alt="{{ __('messages.profilepartialsupdateprofile.profilePhoto') }}" width="80" height="80">
                    @endif
                </div>
            @endif
            <input type="file" class="d-none" id="avatar" name="avatar" onchange="updateFileName(this)">
            <label for="avatar" href="{{ route('profile.edit') }}" class="btn btn-gradient text-color">
                {{ __('messages.profilepartialsupdateprofile.chooseFile')}}
            </label>
            <span id="file-name" class="ms-2">{{ __('messages.profilepartialsupdateprofile.fileNotChoosen') }}</span>

            @if ($errors->get('avatar'))
>>>>>>> origin/wodzu
                <div class="text-danger small mt-1">
                    {{ $errors->first('description') }}
                </div>
            @endif
        </div>

<<<<<<< HEAD
=======

        <script>
            function updateFileName(input) {
                const fileName = input.files.length ? input.files[0].name : __('messages.profilepartialsupdateprofile.fileNotChoosen');
                document.getElementById('file-name').textContent = fileName;
            }
        </script>


>>>>>>> origin/wodzu
        <div class="mb-3 text-color">
            <label for="preferred_language" class="form-label"> {{ __('messages.profilepartialsupdateprofile.preferredLanguage') }}</label>
            <select class="form-select" id="preferred_language" name="preferred_language">
                <option value="pl" {{ $user->language == 'pl' ? 'selected' : '' }}>Polski</option>
                <option value="en" {{ $user->language == 'en' ? 'selected' : '' }}>English</option>
                <option value="de" {{ $user->language == 'de' ? 'selected' : '' }}>Deutsch</option>
                <option value="uk" {{ $user->language == 'uk' ? 'selected' : '' }}>Українська</option>
                <option value="uk" {{ $user->language == 'jpn' ? 'selected' : '' }}>日本語</option>

            </select>
            @if ($errors->get('preferred_language'))
                <div class="text-danger small mt-1">
                    {{ $errors->first('preferred_language') }}
                </div>
            @endif
        </div>

        <div class="mb-4 text-color">
            <label for="theme" class="form-label"> {{ __('messages.profilepartialsupdateprofile.theme') }}</label>
            <select class="form-select" id="theme" name="theme">
                <option value="light" {{ $user->theme == 'light' ? 'selected' : '' }}>{{ __('messages.profilepartialsupdateprofile.light') }}</option>
                <option value="dark" {{ $user->theme == 'dark' ? 'selected' : '' }}>{{ __('messages.profilepartialsupdateprofile.dark') }}</option>
            </select>
            @if ($errors->get('theme'))
                <div class="text-danger small mt-1">
                    {{ $errors->first('theme') }}
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn-gradient text-color"> {{ __('messages.profilepartialsupdateprofile.save') }}
            </button>

            @if (session('status') === 'password-updated')
                <span class="text-success small">
                     {{ __('messages.profilepartialsupdateprofile.saved') }}
                </span>
            @endif
        </div>
    </form>
</section>
