# DB Adapters - Models and APIs Documentation

## Created Models

### 1. TagRecheck Model
**File:** `app/Models/TagRecheck.php`
**Table:** `TagRecheck`
**Primary Key:** `Id`

**Methods:**
- `getTagRecheckByFlight($flightId, $startDate, $endDate)` - Get records by flight and date range
- `insertTagRecheck($data)` - Insert new record
- `updateTagRecheck($data, $id)` - Update record by ID
- `deleteTagRecheck($id)` - Soft delete record
- `getTagRecheckAPI($startDate, $endDate, $limit)` - Get records for API with pagination

### 2. Airlines Model
**File:** `app/Models/Airlines.php`
**Table:** `Airlines`
**Primary Key:** `AirlineId`

**Methods:**
- `getActiveAirlines()` - Get all active airlines
- `insertAirline($data)` - Insert new airline
- `updateAirline($data, $airlineId)` - Update airline by ID
- `getAirlineByIataCode($iataCode)` - Get airline by IATA code
- `getAirlineByIcaoCode($icaoCode)` - Get airline by ICAO code

### 3. Aircrafts Model
**File:** `app/Models/Aircrafts.php`
**Table:** `Aircrafts`
**Primary Key:** `AircraftId`

**Methods:**
- `getActiveAircrafts()` - Get all active aircrafts
- `insertAircraft($data)` - Insert new aircraft
- `updateAircraft($data, $aircraftId)` - Update aircraft by ID
- `getAircraftByRegistration($registration)` - Get aircraft by registration

### 4. AircraftTypes Model
**File:** `app/Models/AircraftTypes.php`
**Table:** `AircraftTypes`
**Primary Key:** `AircraftTypeId`

**Methods:**
- `getActiveAircraftTypes()` - Get all active aircraft types
- `insertAircraftType($data)` - Insert new aircraft type
- `updateAircraftType($data, $aircraftTypeId)` - Update aircraft type by ID
- `getAircraftTypeByIataCode($iataCode)` - Get by IATA code
- `getAircraftTypeByIcaoCode($icaoCode)` - Get by ICAO code

### 5. Airports Model
**File:** `app/Models/Airports.php`
**Table:** `Airports`
**Primary Key:** `AirportId`

**Methods:**
- `getActiveAirports()` - Get all active airports
- `insertAirport($data)` - Insert new airport
- `updateAirport($data, $airportId)` - Update airport by ID
- `getAirportByIataCode($iataCode)` - Get airport by IATA code
- `getAirportByIcaoCode($icaoCode)` - Get airport by ICAO code
- `getAirportsByCity($city)` - Get airports by city

### 6. Route Model
**File:** `app/Models/Route.php`
**Table:** `Route`
**Primary Key:** `Id`

**Methods:**
- `getAllRoutes()` - Get all routes
- `insertRoute($data)` - Insert new route
- `updateRoute($data, $id)` - Update route by ID
- `getRouteByName($name)` - Get route by name
- `getRoutesByCity($city)` - Get routes by city

### 7. Gate Model
**File:** `app/Models/Gate.php`
**Table:** `Gate`
**Primary Key:** `Id`

**Methods:**
- `getAllGates()` - Get all gates
- `insertGate($data)` - Insert new gate
- `updateGate($data, $id)` - Update gate by ID
- `getGateByName($name)` - Get gate by name

### 8. Stand Model
**File:** `app/Models/Stand.php`
**Table:** `Stand`
**Primary Key:** `Id`

**Methods:**
- `getAllStands()` - Get all stands
- `insertStand($data)` - Insert new stand
- `updateStand($data, $id)` - Update stand by ID
- `getStandByName($name)` - Get stand by name

### 9. Chute Model
**File:** `app/Models/Chute.php`
**Table:** `Chute`
**Primary Key:** `Id`

**Methods:**
- `getAllChutes()` - Get all chutes
- `insertChute($data)` - Insert new chute
- `updateChute($data, $id)` - Update chute by ID
- `getChuteByName($name)` - Get chute by name

### 10. Carousel Model
**File:** `app/Models/Carousel.php`
**Table:** `Carousel`
**Primary Key:** `Id`

**Methods:**
- `getAllCarousels()` - Get all carousels
- `insertCarousel($data)` - Insert new carousel
- `updateCarousel($data, $id)` - Update carousel by ID
- `getCarouselByName($name)` - Get carousel by name

## Created API Controllers

### 1. VeribagsApi Controller
**File:** `app/Http/Controllers/API/VeribagsApi.php`

**Endpoints:**
- `GET /api/process-barcode` - Process barcode data for baggage recheck
- `GET /api/recheck-statistics/{date}` - Get recheck statistics by date
- `GET /api/recheck-by-counter/{date}/{counter}` - Get recheck records by counter

**Special Features:**
- Multi-language support (EN, VI, KO, ZH)
- Smart name matching (handles different name orders)
- Seat number matching (handles leading zeros)
- Counter letter extraction from CounterDetail

### 2. TagRecheckApi Controller
**File:** `app/Http/Controllers/API/TagRecheckApi.php`

**Endpoints:**
- `GET /api/tag-recheck/{date}` - Get TagRecheck records by date
- `GET /api/tag-recheck/{flightId}/{date}` - Get TagRecheck records by flight and date
- `POST /api/tag-recheck` - Create new TagRecheck record
- `PUT /api/tag-recheck/{id}` - Update TagRecheck record
- `DELETE /api/tag-recheck/{id}` - Delete TagRecheck record (soft delete)
- `PATCH /api/tag-recheck/{id}/finish` - Mark TagRecheck as finished

### 3. AirlinesApi Controller
**File:** `app/Http/Controllers/API/AirlinesApi.php`

**Endpoints:**
- `GET /api/airlines` - Get all active airlines
- `GET /api/airlines/iata/{iataCode}` - Get airline by IATA code
- `POST /api/airlines` - Create new airline
- `PUT /api/airlines/{airlineId}` - Update airline

### 4. AircraftsApi Controller
**File:** `app/Http/Controllers/API/AircraftsApi.php`

**Endpoints:**
- `GET /api/aircrafts` - Get all active aircrafts
- `GET /api/aircrafts/registration/{registration}` - Get aircraft by registration
- `POST /api/aircrafts` - Create new aircraft
- `PUT /api/aircrafts/{aircraftId}` - Update aircraft

### 5. AircraftTypesApi Controller
**File:** `app/Http/Controllers/API/AircraftTypesApi.php`

**Endpoints:**
- `GET /api/aircraft-types` - Get all active aircraft types
- `GET /api/aircraft-types/icao/{icaoCode}` - Get aircraft type by ICAO code
- `POST /api/aircraft-types` - Create new aircraft type
- `PUT /api/aircraft-types/{aircraftTypeId}` - Update aircraft type

### 6. AirportsApi Controller
**File:** `app/Http/Controllers/API/AirportsApi.php`

**Endpoints:**
- `GET /api/airports` - Get all active airports
- `GET /api/airports/iata/{iataCode}` - Get airport by IATA code
- `GET /api/airports/city/{city}` - Get airports by city
- `POST /api/airports` - Create new airport
- `PUT /api/airports/{airportId}` - Update airport

## Key Features Implemented

### Barcode Processing API
The main feature is the barcode processing API that:
1. Takes barcode scan data as input parameters
2. First checks TagRecheck table for recheck requirements
3. If not found, checks DepartureMovementView for flight information
4. Returns appropriate response with multilingual messages
5. Handles various edge cases (name order, leading zeros in seat numbers, etc.)

### Validation and Error Handling
All APIs include:
- Input validation using Laravel's validation rules
- Proper error handling with try-catch blocks
- Consistent JSON response format
- Appropriate HTTP status codes

### Database Abstraction
All models use:
- Dynamic fillable columns based on database schema
- Disabled timestamps for tables without created_at/updated_at
- Custom methods for common operations
- Proper table name configurations

## Usage Examples

### Process Barcode Data
```
GET /api/process-barcode?airline=VJ&flightNumber=870&flightDate=2025-03-29&seatNumber=15F&lastName=KIM&firstName=SOOJINMS&languageCode=EN
```

### Create TagRecheck Record
```
POST /api/tag-recheck
Content-Type: application/json

{
    "FlightId": "VJ870",
    "ScheduledDatetime": "2025-03-29 01:00:00",
    "NamePassenger": "KIM SOOJINMS",
    "SeatNumber": "15F",
    "TagNumber": "0523026119",
    "RouteName": "TAE",
    "HandlerDep": "SAGS",
    "CounterDetail": "H6,H7,H8",
    "City": "DAEGU"
}
```

### Get Recheck Statistics
```
GET /api/recheck-statistics/2025-03-29
```

All APIs are now ready for use and follow Laravel best practices for structure, validation, and error handling.
