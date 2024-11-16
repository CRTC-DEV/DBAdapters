<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
  
class ArrivalMovement extends Model
{
    protected $table = 'arrival_movements';

    // Định nghĩa các cột có thể ghi được, giữ nguyên tên cột với viết hoa ký tự đầu
    protected $fillable = [
        'Id',
        'MovementId',
        'AircraftId',
        'AircraftTypeId',
        'MovementDatetime',
        'RouteId',
        'AirlineId',
        'FlightId',
        'ScheduledDatetime',
        'StandId',
        'CarouselId',
        'PaxAdult',
        'EstimatedTime',
        'ConfidentTime',
        'PaxChild',
        'ConfidentPaxCount',
        'StatusArr',
        'Qualifier',
        'FirstBagTime',
        'LastBagTime',
        'HandlerArr',
        'PBB1Start',
        'PBB1End',
        'PBB2Start',
        'PBB2End',
        'PBB3Start',
        'PBB3End',
        'ChocksOn',
        'ChocksOff',
        'PaxInfant',
        'NonOperational',
        'NonSeasonal',
        'StandAllocation',
        'BatchDate',
        'BaggageLoadTotal',
        'MovementSourceDataVersion',
        'ResponseCode',
        'Status',
        'Status_B',
        'Status_A',
        'ResponsedDate',
        'CreateDate',
    ];

    // Bạn có thể thêm các phương thức khác nếu cần
}




























