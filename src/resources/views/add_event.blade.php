<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Faily - dodaj wydarzenie</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-main">
  <div class="page-container">
      @include('includes.navbar')
    <main>
      <div class="container mt-5">
        <h2>Dodaj wydarzenie</h2>
        <form>
          <div class="mb-3">
            <label class="form-label">Lokalizacja</label>
            <input type="text" class="form-control" placeholder="Miasto" required>
            <input type="text" class="form-control mt-2" placeholder="Ulica" required>
            <input type="text" class="form-control mt-2" placeholder="Numer budynku" required>
            <input type="text" class="form-control mt-2" placeholder="Numer mieszkania (opcjonalnie)">
          </div>
          <div class="mb-3">
            <label for="eventPhotos" class="form-label">Dodaj zdjęcia</label>
            <input type="file" class="form-control" id="eventPhotos" multiple onchange="updateFileList()">
            <ul id="fileList" class="mt-2"></ul>
          </div>
          <div class="mb-3">
            <label for="peopleCount" class="form-label">Ilość osób</label>
            <input type="number" class="form-control" id="peopleCount" min="1" required>
          </div>
          <div class="mb-3">
            <label for="description" class="form-label">Opis wydarzenia</label>
            <textarea class="form-control" id="description" rows="4" required></textarea>
          </div>
          <button type="submit" class="btn btn-primary btn-lg">Dodaj ogłoszenie do oferty</button>
        </form>
      </div>
    </main>
      @include('includes.footer')
  </div>
  <script>
    function updateFileList() {
      const input = document.getElementById("eventPhotos");
      const list = document.getElementById("fileList");
      list.innerHTML = "";
      for (const file of input.files) {
        const li = document.createElement("li");
        li.textContent = file.name;
        list.appendChild(li);
      }
    }
  </script>
</body>
</html>
