@extends('livewire.layouts.main')

@section('content')
<div class="container">
    <h1 class="text-center my-4">Flight Schedule</h1>
    <ul class="nav nav-tabs" id="flightTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="arrivals-tab" data-bs-toggle="tab" data-bs-target="#arrivals" type="button" role="tab" aria-controls="arrivals" aria-selected="true">
                Arrivals
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="departures-tab" data-bs-toggle="tab" data-bs-target="#departures" type="button" role="tab" aria-controls="departures" aria-selected="false">
                Departures
            </button>
        </li>
    </ul>
    <div class="tab-content" id="flightTabsContent">
        <div class="tab-pane fade show active" id="arrivals" role="tabpanel" aria-labelledby="arrivals-tab">
           
        </div>
        <div class="tab-pane fade" id="departures" role="tabpanel" aria-labelledby="departures-tab">
          
        </div>
    </div>
</div>
@endsection
