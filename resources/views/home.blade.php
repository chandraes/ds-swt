@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-left">
        <div class="col-3 text-center">
            <a href="{{route('pengaturan')}}" class="text-decoration-none">
                <img src="{{asset('images/database.svg')}}" alt="" width="100">
                <h2>Database</h2>
            </a>
        </div>
        <div class="col-3 text-center">
            <a href="{{route('pengaturan')}}" class="text-decoration-none">
                <img src="{{asset('images/pengaturan.svg')}}" alt="" width="100">
                <h2>Pengaturan</h2>
            </a>
        </div>
        {{-- <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <div class="mb-3">
                        <label for="number" class="form-label">Number</label>
                        <input type="text" class="form-control" name="number" id="number" aria-describedby="helpId"
                            placeholder="">
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
</div>
@endsection
@push('js')
<script src="{{asset('assets/js/cleave.min.js')}}"></script>
<script>
    // use cleave.js to format the number
    var cleave = new Cleave('#number', {
        numeral: true,
        numeralThousandsGroupStyle: 'thousand',
        numeralDecimalMark: ',',
        delimiter: '.'
    });
</script>
@endpush
