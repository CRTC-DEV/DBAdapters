<div>
    <h2 class="text-center mb-4">Arrivals</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Flight No</th>
                <th>Airline</th>
                <th>Origin</th>
                <th>Terminal</th>
                <th>Arrival Time</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($arrivals as $arrival)
                <tr wire:click="toggleDetails({{ $arrival->id }})" style="cursor: pointer;">
                    <td>{{ $arrival->flight_no }}</td>
                    <td>{{ $arrival->airline }}</td>
                    <td>{{ $arrival->origin }}</td>
                    <td>{{ $arrival->terminal }}</td>
                    <td>{{ $arrival->arrival_time }}</td>
                    <td>
                        <button class="btn btn-info btn-sm">Details</button>
                    </td>
                </tr>
                
                @if($selectedArrivalId === $arrival->id)
                    <tr>
                        <td colspan="6">
                            <div>
                                <strong>Flight Details:</strong>
                                <ul>
                                    <li><strong>Flight No:</strong> {{ $arrival->flight_no }}</li>
                                    <li><strong>Airline:</strong> {{ $arrival->airline }}</li>
                                    <li><strong>Origin:</strong> {{ $arrival->origin }}</li>
                                    <li><strong>Terminal:</strong> {{ $arrival->terminal }}</li>
                                    <li><strong>Arrival Time:</strong> {{ $arrival->arrival_time }}</li>
                                    <!-- Thêm bất kỳ thông tin chi tiết nào khác ở đây -->
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>
