<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.title.editEvent') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-main">
@include('includes.navbar')

<div class="container py-5 text-white">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>{{ __('messages.editevent.editTitle') }}</h2>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('events.show', $event) }}" class="btn text-white border-dark" style="background: linear-gradient(135deg, #5a00a0 0%, #7f00d4 100%);">{{ __('messages.editevent.backToEvent') }}</a>
        </div>
    </div>

    <div class="card border-dark shadow-sm custom-card-bg mb-4">
        <div class="card-header text-white" style="background: linear-gradient(45deg, #5a4e82 0%, #3a6b8a 100%);" >
            <h4 class="mb-0">{{ __('messages.editevent.formTitle') }}</h4>
        </div>
        <div class="card-body text-white border-black" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
            <form action="{{ route('events.update', $event) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="mb-3">
                    <label for="title" class="form-label">{{ __('messages.editevent.eventName') }}</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $event->title) }}" required>
                    @error('title')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">{{ __('messages.editevent.eventDesc') }}</label>
                    <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description', $event->description) }}</textarea>
                    @error('description')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="date" class="form-label">{{ __('messages.editevent.eventDateTime') }}</label>
                    <input type="datetime-local" class="form-control" id="date" name="date" value="{{ old('date', $event->date->format('Y-m-d\TH:i')) }}" required>
                    @error('date')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="location_name" class="form-label">{{ __('messages.editevent.locationName') }}</label>
                    <input type="text" class="form-control" id="location_name" name="location_name" value="{{ old('location_name', $event->location_name) }}" required>
                    @error('location_name')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="latitude" class="form-label">{{ __('messages.editevent.latitude') }}</label>
                        <input type="number" step="any" class="form-control" id="latitude" name="latitude" value="{{ old('latitude', $event->latitude) }}" required>
                        @error('latitude')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="longitude" class="form-label">{{ __('messages.editevent.longitude') }}</label>
                        <input type="number" step="any" class="form-control" id="longitude" name="longitude" value="{{ old('longitude', $event->longitude) }}" required>
                        @error('longitude')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="people_count" class="form-label">{{ __('messages.editevent.participantLimit') }}</label>
                    <input type="number" class="form-control" id="people_count" name="people_count" value="{{ old('people_count', $event->people_count) }}" required min="1">
                    @error('people_count')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="has_ride_sharing" name="has_ride_sharing" value="1" {{ old('has_ride_sharing', $event->has_ride_sharing) ? 'checked' : '' }}>
                    <label class="form-check-label" for="has_ride_sharing">{{ __('messages.editevent.carSharingOption') }}</label>
                    @error('has_ride_sharing')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <div><label for="photos" class="form-label">{{ __('messages.editevent.uploadPhotos') }}</label></div>
                    <div><small class="text-white">{{ __('messages.editevent.uploadHint') }}</small></div>
                    <input type="file" class="d-none" id="photos" name="photos[]" multiple accept="image/*" onchange="updateFileList()">
                    <label for="photos" class="btn text-white border-dark mt-2" style="background: linear-gradient(135deg, #5a00a0 0%, #7f00d4 100%);">
                        {{ __('messages.addevent.addPhotos') }}
                    </label>
                    <div id="fileList" class="mt-2 small text-white">{{ __('messages.addevent.fileNotChoosen') }}</div>
                    @error('photos')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                    @error('photos.*')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <script>
                    function updateFileList() {
                        const input = document.getElementById('photos');
                        const fileList = document.getElementById('fileList');
                        const files = input.files;

                        if (!files.length) {
                            fileList.textContent = "{{ __('messages.addevent.fileNotChoosen') }}";
                            return;
                        }

                        let list = Array.from(files).map(file => file.name).join(', ');
                        fileList.textContent = list;
                    }
                </script>


                <div class="mt-4">
                    <button type="submit" class="btn text-white" style="background: linear-gradient(135deg, #5a00a0 0%, #7f00d4 100%);">{{ __('messages.editevent.submitChanges') }}</button>
                    <a href="{{ route('events.show', $event) }}" class="btn btn-secondary">{{ __('messages.editevent.cancelChanges') }}</a>
                </div>
            </form>
        </div>
    </div>

    @if($event->photos->count() > 0)
        <div class="card border-0 shadow-lg rounded-4 custom-card-bg mb-5 overflow-hidden">
            <div class="card-header text-white" style="background: linear-gradient(45deg, #5a4e82 0%, #3a6b8a 100%);">
                <h4 class="mb-0">{{ __('messages.editevent.existingPhotos') }}</h4>
            </div>
            <div class="card-body border-black" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
                <div class="row g-4">
                    @foreach($event->photos as $photo)
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm bg-dark rounded-4 overflow-hidden">
                                <img src="{{ asset('storage/' . $photo->path) }}" class="card-img-top rounded-top" alt="{{ __('messages.editevent.photoLabel') }}" style="object-fit: cover; height: 250px;">
                                <div class="card-body text-center">
                                    <form action="{{ route('photos.destroy', $photo) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm rounded-pill mt-2" onclick="return confirm('{{ __('messages.editevent.deletePhotoConfirm') }}')">
                                            {{ __('messages.editevent.deletePhoto') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif


</div>

@include('includes.footer')
</body>
</html>
