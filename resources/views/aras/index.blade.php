<form action="{{ route('aras.hitung') }}" method="GET" class="space-y-4 mb-6">
    <div class="grid grid-cols-3 gap-4">
        <div>
            <label>Bobot Semester 1</label>
            <input type="number" name="bobot[sem_1]" step="0.01" class="input input-bordered w-full" value="{{ request('bobot.sem_1') ?? 0.15 }}" required>
        </div>
        <div>
            <label>Bobot Semester 2</label>
            <input type="number" name="bobot[sem_2]" step="0.01" class="input input-bordered w-full" value="{{ request('bobot.sem_2') ?? 0.15 }}" required>
        </div>
        <div>
            <label>Bobot Semester 3</label>
            <input type="number" name="bobot[sem_3]" step="0.01" class="input input-bordered w-full" value="{{ request('bobot.sem_3') ?? 0.15 }}" required>
        </div>
        <div>
            <label>Bobot Semester 4</label>
            <input type="number" name="bobot[sem_4]" step="0.01" class="input input-bordered w-full" value="{{ request('bobot.sem_4') ?? 0.15 }}" required>
        </div>
        <div>
            <label>Bobot Semester 5</label>
            <input type="number" name="bobot[sem_5]" step="0.01" class="input input-bordered w-full" value="{{ request('bobot.sem_5') ?? 0.20 }}" required>
        </div>
        <div>
            <label>Bobot Prestasi</label>
            <input type="number" name="bobot[prestasi]" step="0.01" class="input input-bordered w-full" value="{{ request('bobot.prestasi') ?? 0.20 }}" required>
        </div>
    </div>

    <button type="submit" class="btn btn-primary mt-4">Hitung ARAS</button>
</form>
