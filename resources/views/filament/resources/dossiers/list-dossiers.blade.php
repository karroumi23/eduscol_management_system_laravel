<x-filament-panels::page>

    {{-- Participant filter header --}}
      @if(isset($participant) && $participant)
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem; background:#ecfdf5; padding:16px; border-radius:12px; border:1px solid #a7f3d0;">
            <div style="display:flex; align-items:center; gap:12px;">
                @if($participant->photo)
                    <img src="{{ Storage::url($participant->photo) }}"
                        style="width:50px; height:50px; border-radius:50%; object-fit:cover; border:2px solid #059669;" />
                @else
                    <div style="width:50px; height:50px; border-radius:50%; background:#059669; display:flex; align-items:center; justify-content:center;">
                        <span style="color:white; font-weight:bold; font-size:1.3rem;">
                            {{ substr($participant->nom, 0, 1) }}
                        </span>
                    </div>
                @endif
                <div style="display:flex; align-items:center">
                    <p style="font-size:17px; font-weight:700; color:#788a85; margin:0;">
                        Dossiers de :  <p style="font-size:25px; font-weight:700; color:#065f46; margin:0;"> {{ $participant->nom }}</p>
                    </p>

                </div>
            </div>
            <a href="{{ \App\Filament\Resources\Dossiers\DossierResource::getUrl('index') }}"
                style="background:#6b7280; color:white; padding:8px 16px; border-radius:8px; text-decoration:none; font-size:13px; font-weight:600;">
                ← Tous les dossiers
            </a>
        </div>
      @endif

    {{-- Filters --}}
    <div style="background:#f9fafb; padding:20px; border-radius:12px; border:1px solid #e5e7eb; margin-bottom:1.5rem;">

        {{-- Filter header --}}
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px;">
            <p style="font-size:13px; font-weight:700; color:#374151; margin:0;">🔍 Recherche & Filtres</p>
            <a href="{{ \App\Filament\Resources\Dossiers\DossierResource::getUrl('index') }}"
                style="background:#db0e0e; color:white; padding:4px 8px; border-radius:8px; text-decoration:none; font-size:12px; font-weight:600;">
                ✖ Reset
            </a>
        </div>

        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(200px, 1fr)); gap:12px;">

            @if(request()->query('participant_id'))
                <input type="hidden" id="participant_id_val" value="{{ request()->query('participant_id') }}">
            @endif

            <div style="display:flex; flex-direction:column; gap:4px;">
                <label style="font-size:12px; font-weight:600; color:#374151;">Nom</label>
                <input
                    type="text"
                    id="search_nom"
                    value="{{ request()->query('search_nom') }}"
                    placeholder="Rechercher par nom..."
                    oninput="autoFilter()"
                    style="padding:9px 12px; border:1px solid #d1d5db; border-radius:8px; font-size:13px; color:#111827; background:white; outline:none; width:100%; box-sizing:border-box;">
            </div>

            <div style="display:flex; flex-direction:column; gap:4px;">
                <label style="font-size:12px; font-weight:600; color:#374151;">CIN</label>
                <input
                    type="text"
                    id="search_cin"
                    value="{{ request()->query('search_cin') }}"
                    placeholder="Rechercher par CIN..."
                    oninput="autoFilter()"
                    style="padding:9px 12px; border:1px solid #d1d5db; border-radius:8px; font-size:13px; color:#111827; background:white; outline:none; width:100%; box-sizing:border-box;">
            </div>

            <div style="display:flex; flex-direction:column; gap:4px;">
                <label style="font-size:12px; font-weight:600; color:#374151;">Catégorie</label>
                <select
                    id="categorie_formation"
                    onchange="autoFilter()"
                    style="padding:9px 12px; border:1px solid #d1d5db; border-radius:8px; font-size:13px; color:#111827; background:white; outline:none; width:100%; box-sizing:border-box;">
                    <option value="">Toutes</option>
                    <option value="TRM" {{ request()->query('categorie_formation') === 'TRM' ? 'selected' : '' }}>TRM</option>
                    <option value="TRV" {{ request()->query('categorie_formation') === 'TRV' ? 'selected' : '' }}>TRV</option>
                </select>
            </div>

            <div style="display:flex; flex-direction:column; gap:4px;">
                <label style="font-size:12px; font-weight:600; color:#374151;">Type de formation</label>
                <select
                    id="type_formation"
                    onchange="autoFilter()"
                    style="padding:9px 12px; border:1px solid #d1d5db; border-radius:8px; font-size:13px; color:#111827; background:white; outline:none; width:100%; box-sizing:border-box;">
                    <option value="">Tous</option>
                    <option value="FQIMO" {{ request()->query('type_formation') === 'FQIMO' ? 'selected' : '' }}>FQIMO</option>
                    <option value="FCO" {{ request()->query('type_formation') === 'FCO' ? 'selected' : '' }}>FCO</option>
                </select>
            </div>

        </div>
    </div>

    {{-- Header button   + Nouveau Dossier --}}
    <div style="display:flex; justify-content:flex-end; margin-bottom:1.5rem;">
        <a href="{{ \App\Filament\Resources\Dossiers\DossierResource::getUrl('create') }}"
            style="display:inline-flex; align-items:center; gap:8px; background:#059669; color:white; padding:10px 20px; border-radius:10px; font-weight:600; text-decoration:none; font-size:14px; box-shadow:0 2px 6px rgba(0,0,0,0.15);">
            + Nouveau Dossier
        </a>
    </div>

     @if($records->isEmpty())
        <div style="text-align:center; padding:4rem; color:#9ca3af;">
            <p style="font-size:1.5rem;">Aucun dossier trouvé</p>
        </div>
     @else
        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(320px, 1fr)); gap:1.5rem;">
            @foreach($records as $dossier)
                <div style="background:white; border-radius:16px; box-shadow:0 4px 15px rgba(0,0,0,0.08); overflow:hidden; border:1px solid #e5e7eb;">

            {{-- Header --}}
            <div style="background:#059669; padding:16px; display:flex; align-items:center; justify-content:space-between;">
                <div style="display:flex; align-items:center; gap:12px;">
                    @if($dossier->participant?->photo)
                        <img src="{{ Storage::url($dossier->participant->photo) }}"
                            style="width:44px; height:44px; border-radius:50%; object-fit:cover; border:2px solid white;" />
                    @else
                        <div style="width:44px; height:44px; border-radius:50%; background:rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center;">
                            <span style="color:white; font-weight:bold; font-size:1.2rem;">
                                {{ substr($dossier->participant?->nom ?? 'N', 0, 1) }}
                            </span>
                        </div>
                    @endif
                    <div>
                        <p style="color:white; font-weight:800; font-size:16px; margin:0; text-transform:uppercase; letter-spacing:0.5px;">
                            {{ $dossier->participant?->nom ?? $dossier->nom }}
                        </p>
                        <p style="color:#a7f3d0; font-size:12px; margin:4px 0 0;"># Dossier {{ $dossier->id }}</p>
                    </div>
                </div>
                <div style="display:flex; flex-direction:column; gap:4px; align-items:flex-end;">
                    <span style="padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700;
                        background:{{ $dossier->categorie_formation === 'TRM' ? '#3b82f6' : '#8b5cf6' }}; color:white;">
                        {{ $dossier->categorie_formation }}
                    </span>
                    <span style="padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700;
                        background:{{ $dossier->type_formation === 'FCO' ? '#ef4444' : '#f59e0b' }}; color:white;">
                        {{ $dossier->type_formation }}
                    </span>
                </div>
            </div>

                    {{-- Body --}}
                    <div style="padding:16px; display:flex; flex-direction:column; gap:10px;">
                        <div style="display:flex; align-items:center; gap:8px; font-size:13px; color:#4b5563;">
                            <span>🪪</span>
                            <span>CIN: <strong>{{ $dossier->participant?->cin ?? 'N/A' }}</strong></span>
                        </div>

                        <div style="display:flex; align-items:center; gap:8px; font-size:13px; color:#4b5563;">
                            <span>🗓️</span>
                            <span>DATE_NAISSANCE: <strong>{{ $dossier->date_naissance }}</strong></span>
                        </div>

                        <div style="display:flex; align-items:center; gap:8px; font-size:13px; color:#4b5563;">
                            <span>📞</span>
                            <span>{{ $dossier->participant?->telephone ?? 'N/A' }}</span>
                        </div>

                        <div style="display:flex; align-items:center; gap:8px; font-size:13px; color:#4b5563;">
                            <span>🪪</span>
                            <span>Permis: <strong>{{ $dossier->participant?->num_permis ?? 'N/A' }}</strong></span>
                        </div>

                        <div style="display:flex; align-items:center; gap:8px; font-size:13px; color:#4b5563;">
                            <span>🎓</span>
                            <span>Formateur: <strong>{{ $dossier->nom_formateur }}</strong></span>
                        </div>

                        {{-- Dates --}}
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                            <div style="background:#ecfdf5; border-radius:10px; padding:10px; text-align:center;">
                                <p style="font-size:11px; color:#6b7280; margin:0;">Début</p>
                                <p style="font-size:13px; font-weight:700; color:#059669; margin:4px 0 0;">
                                    {{ \Carbon\Carbon::parse($dossier->date_depart_formation)->format('d/m/Y') }}
                                </p>
                            </div>
                            <div style="background:#fef2f2; border-radius:10px; padding:10px; text-align:center;">
                                <p style="font-size:11px; color:#6b7280; margin:0;">Fin</p>
                                <p style="font-size:13px; font-weight:700; color:#dc2626; margin:4px 0 0;">
                                    {{ \Carbon\Carbon::parse($dossier->date_fin_formation)->format('d/m/Y') }}
                                </p>
                            </div>
                        </div>

                        {{-- Prix --}}
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                            <div style="background:#059669; border-radius:10px; padding:10px; text-align:center;">
                                <p style="font-size:11px; color:#a7f3d0; margin:0;">Prix Total</p>
                                <p style="font-size:13px; font-weight:700; color:white; margin:4px 0 0;">
                                    {{ number_format($dossier->prix_formation, 2) }} MAD
                                </p>
                            </div>
                            <div style="background:#f59e0b; border-radius:10px; padding:10px; text-align:center;">
                                <p style="font-size:11px; color:#fef3c7; margin:0;">Acompte</p>
                                <p style="font-size:13px; font-weight:700; color:white; margin:4px 0 0;">
                                    {{ number_format($dossier->acompte, 2) }} MAD
                                </p>
                            </div>
                        </div>

                        {{-- Reste --}}
                        <div style="background:#eff6ff; border-radius:10px; padding:10px; text-align:center;">
                            <p style="font-size:11px; color:#6b7280; margin:0;">Montant restant</p>
                            <p style="font-size:14px; font-weight:700; color:#2563eb; margin:4px 0 0;">
                                {{ number_format($dossier->prix_formation - $dossier->acompte, 2) }} MAD
                            </p>
                        </div>

                        {{-- Créé par --}}
                        <div style="display:flex; align-items:center; gap:6px; font-size:12px; color:#9ca3af;">
                            <span>👤</span>
                            <span>Créé par: {{ $dossier->createdBy->name ?? 'N/A' }}</span>
                        </div>
                    </div>

                    {{-- Footer actions --}}
                    <div style="padding:12px 16px 16px; display:flex; gap:8px;">
                        <a href="{{ \App\Filament\Resources\Dossiers\DossierResource::getUrl('edit', ['record' => $dossier->id]) }}"
                            style="flex:1; text-align:center; background:#f59e0b; color:white; font-size:13px; font-weight:600; padding:8px; border-radius:8px; text-decoration:none;">
                            ✏️ Modifier
                        </a>
                        <button
                            onclick="deleteDossier({{ $dossier->id }})"
                            style="flex:1; background:#ef4444; color:white; font-size:13px; font-weight:600; padding:8px; border-radius:8px; border:none; cursor:pointer;">
                            🗑️ Supprimer
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div style="margin-top:2rem;">
            {{ $records->links() }}
        </div>
    @endif

</x-filament-panels::page>



<script>
    function deleteDossier(id) {
        if (!confirm('Voulez-vous vraiment supprimer ce dossier ?')) return;
        fetch(`/admin/dossiers/dossiers/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        }).then(response => {
            if (response.ok) {
                window.location.reload();
            } else {
                alert('Erreur lors de la suppression.');
            }
        });
    }
</script>
{{-- filter recherche --}}
<script>
    let filterTimer = null;

    function autoFilter() {
        clearTimeout(filterTimer);
        filterTimer = setTimeout(function() {
            const nom = document.getElementById('search_nom').value;
            const cin = document.getElementById('search_cin').value;
            const categorie = document.getElementById('categorie_formation').value;
            const type = document.getElementById('type_formation').value;
            const participantId = document.getElementById('participant_id_val')?.value ?? '';

            const params = new URLSearchParams();
            if (nom) params.set('search_nom', nom);
            if (cin) params.set('search_cin', cin);
            if (categorie) params.set('categorie_formation', categorie);
            if (type) params.set('type_formation', type);
            if (participantId) params.set('participant_id', participantId);

            window.location.href = '{{ \App\Filament\Resources\Dossiers\DossierResource::getUrl('index') }}?' + params.toString();
        }, 600);
    }
    </script>