@if ($restaurant->exists)
<form action="{{route('admin.restaurants.update', $restaurant->id)}}" method="POST" enctype="multipart/form-data" id="registrationForm">
    @method('PUT')
    
@else
    
    <form action="{{route('admin.restaurants.store')}}" method="POST" enctype="multipart/form-data"  id="registrationForm">
    
@endif
    @csrf
    <div class="glass-card p-5">
        <div class="row">

        {{-- Input Nome --}}
        <div class="col-4">
            <div class="mb-5">
                <label for="restaurant_name" class="form-label">Nome del ristorante<span class="text-danger"><strong>*</strong></span></label>
                <input name="restaurant_name" value="{{old('restaurant_name', $restaurant->restaurant_name)}}" type="text" class="form-control bg-transparent border-dark-light rounded-pill @error('restaurant_name') is-invalid @elseif(old('restaurant_name', '')) is-valid @enderror"  id="restaurant_name">
                @error('restaurant_name')   
                    <div class="invalid-feedback">{{$message}}</div>
                @else
                    <div class="form-text">
                        Inserisci il nome del ristorante
                    </div>
                @enderror
            </div>
        </div>

            {{-- Input Indirizzo --}}
            <div class="col-4">
                <div class="mb-5">
                    <label for="address" class="form-label">Indirizzo<span class="text-danger"><strong>*</strong></span></label>
                    <input name="address" value="{{old('address', $restaurant->address)}}" type="text" class="form-control bg-transparent border-dark-light rounded-pill @error('address') is-invalid @elseif(old('address', '')) is-valid @enderror" id="address">
                    @error('address')   
                        <div class="invalid-feedback">{{$message}}</div>
                    @else
                        <div class="form-text">
                            Inserisci l'indirizzo del ristorante
                        </div>
                    @enderror
                </div>
            </div>

        {{-- Input Numero di telefono --}}
        <div class="col-4">
            <div class="mb-5">
                <label for="phone" class="form-label">Numero di telefono</label>
                <input name="phone" value="{{old('phone', $restaurant->phone)}}" type="text" class="form-control bg-transparent border-dark-light rounded-pill @error('phone') is-invalid @elseif(old('phone', '')) is-valid @enderror" id="phone">
                @error('phone')   
                    <div class="invalid-feedback">{{$message}}</div>
                @else
                    <div class="form-text">
                        Inserisci il numero di telefono del ristorante
                    </div>
                @enderror
            </div>
        </div>

            {{-- Input P.IVA (VAT) --}}
            <div class="col-4">
                <div class="mb-5">
                    <label for="vat" class="form-label">P.IVA<span class="text-danger"><strong>*</strong></span></label>
                    <input name="vat" value="{{old('vat', $restaurant->vat)}}" type="text" class="form-control bg-transparent border-dark-light rounded-pill @error('vat') is-invalid @elseif(old('vat', '')) is-valid @enderror" id="vat">
                    @error('vat')   
                        <div class="invalid-feedback">{{$message}}</div>
                    @else
                        <div class="form-text">
                            Inserisci la P.IVA del ristorante
                        </div>
                    @enderror
                </div>
            </div>

            {{-- Input Immagine --}}
            <div class="col-6 col-sm-6 col-xl-7 mb-3">
                <div class="d-flex flex-column">
                    <label class="form-label label">Upload Immagine:</label>
                    <input type="file" name="image" id="image" value="{{old('image', $restaurant->image)}}" class="form-control bg-transparent border-dark-light rounded-pill @error('image') is-invalid @elseif(old('image')) is-valid @enderror">
                    <label for="image" role="button" id="upload-label" class="btn border-light-subtle rounded-pill">Carica un'immagine</label>
                    @error('image')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @else
                        <div class="valid-feedback">
                            Campo corretto
                        </div>
                    @enderror
                </div>
            </div>

        {{-- Preview Image --}}
        <div class="col-1 d-flex align-items-center">
            <div class="mb-5">
                <img class="img-fluid" src="{{old('image', $restaurant->image) ? asset('storage/'. $restaurant->image) : 'https://marcolanci.it/boolean/assets/placeholder.png'}}" alt="immagine del ristorante" id="preview">
            </div>
        </div>

        {{-- CheckBox Categorie --}}
        <div class="col-12 mb-4">
            <p class="mb-3">Categorie</p>

                    {{-- Foreach delle categorie --}}
                @foreach ($categories as $category)
                    <div class="form-check form-check-inline me-2">
                        <label class="form-check-label" for="category-{{$category->id}}">{{$category->label}}</label>
                        <input class="form-check-input" type="checkbox" id="category-{{$category->id}}" value="{{$category->id}}" name="categories[]" @if (in_array($category->id, old('categories', $prev_categories ?? []))) checked @endif>
                    </div>
                @endforeach
            </div>
        </div>
    <p class="asterisk mb-4">I campi contrassegnati con <span class="text-danger"><strong>*</strong></span> sono obbligatori</p>

    {{-- Bottoni --}}
    <div class="col d-flex justify-content-between">

        {{-- Se lo User ha un ristorante --}}
        @if (Auth::user()->restaurant)
        <a href="{{route('admin.restaurants.show', Auth::user()->restaurant)}}" class="btn btn-secondary"><i class="fa-solid fa-left-long me-2"></i>Torna indietro</a>

        {{-- Se lo User non ha ancora un ristorante --}}
        @else
        <a href="{{route('dashboard')}}" class="btn btn-secondary"><i class="fa-solid fa-left-long me-2"></i>Torna indietro</a>
        @endif
        <div>
            <button class="btn btn-success me-2" type="submit"><i class="fa-solid fa-floppy-disk me-2"></i>Salva</button>
            <button class="btn btn-warning" type="reset"><i class="fa-solid fa-arrows-rotate me-2"></i>Svuota</button>
        </div>
    </div>
</div>
</form> 

@section('scripts')
<script>
    // setto la visualizzazione dinamica dell'immagine in pagina
    const imageField = document.getElementById('image');
    const previewField = document.getElementById('preview');

    let blobUrl;

    imageField.addEventListener('change', () => {

        // controllo se ho il file
        if (imageField.files && imageField.files[0]){
            
            //prendo il file
            const file = imageField.files[0];

            //preparo l'url
            const blobUrl = URL.createObjectURL(file);

            previewField.src = blobUrl;
        }
    })

    window.addEventListener('beforeunload', ()=> {
        if(blobUrl) URL.revokeObjectURL(blobUrl);
    })
</script>
@endsection
@section('scripts')

    <script>
        // const form = document.getElementById('registrationForm');
        const name = document.getElementById('restaurant_name');
        // console.log(form)
        document.getElementById('registrationForm').addEventListener('submit',function(){
            //Creo un flag per gli errori
            let isValid=true;
            console.log('pippo')

            if(!name.value.trim()){
                console.log('ciao')
                isValid=false;
                name.classList.add('is-invalid');
                name.classList.remove('is-valid');
            }else{
                console.log('hello')

                name.classList.remove('is-invalid');
                name.classList.add('is-valid');
            }

            if(!isValid){
                console.log('world')
                event.preventDefault();
            }

        })
    </script>
@endsection