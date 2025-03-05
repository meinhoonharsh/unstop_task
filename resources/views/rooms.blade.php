@php

    $rooms = \App\Models\Room::orderBy('floor')->orderBy('room_number')->get();
    $bookedRooms = session('bookedRooms', []);

    $roomStatus = function ($room_number, $isbooked) use ($bookedRooms) {
        if (in_array($room_number, $bookedRooms)) {
            return 'selected';
        }
        return $isbooked ? 'booked' : 'available';
    };
@endphp
<!DOCTYPE html>
<html>

<head>
    <title>Unstop Task by Harsh Vishwakarma</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>

        .main-container {
            display: flex;
            flex-direction: row;
            justify-content: center;
            gap:20px;
            
            padding: 20px;
        }

        .project {
            max-width: 700px;
        }

        .right {
            padding: 10px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 5px;
            height: fit-content;
        }

        .top {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            display: flex;
            justify-content: space-around;
        }

        .top>div {
            display: flex;
            gap: 20px
        }

        .bottom {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .room-container {
            display: flex;
            justify-content: space-around;
            flex-direction: column-reverse;
        }

        .floor {
            width: 100%;
            padding: 5px;
            display: flex;
            gap: 10px;

            h5 {
                width: 5em;
            }

        }

        .room {
            aspect-ratio: 1/1;
            width: 40px;
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #bbb;
            font-size: 0.8em;
            font-weight: 600
        }

        .room.available {
            background: rgb(185, 255, 185);
        }

        .room.booked {
            background: rgb(213, 213, 213);
        }

        .room.selected {
            background: rgb(253, 253, 127);
            border: 2px solid black;
        }


        .room-info{
            display: flex;
            flex-direction: column;
            gap: 10px;

            >div{
                display: flex;
                gap: 10px;
                align-items: center;
                font-size: 1.1em;
                font-weight: 500;
                span{
                    font-size: 0.7em;
                }

            }
        }

        .user-profile{
            display: flex;
            gap: 10px;
            align-items: center;
            img{
                width: 60px;
                height: 60px;
                border-radius: 10px;
            }
            h6{
                font-size: 1.2em;
                font-weight: 600;
                line-height: 0;
            }
            span{
                font-size: 0.8em;
                color: #444;
                line-height: 0;
            }
        }

        .user-socials{
            display: flex;
            gap: 10px;
            margin-top: 10px;
            a{
                border: 0.5px solid #444;
                color: #444;
                font-size: 0.8em;
                padding: 5px 10px;
                border-radius: 5px;
                text-decoration: none;
            }
        }

    </style>

</head>

<body>

    <div class="main-container">

        <div class="project">
            <div class="top">
                <form method="POST" action="{{ route('book') }}">
                    @csrf
                    <div class="input-group">

                        <label class="input-group-text" for="inputGroupSelect04">Number of Rooms (1-5):</label>

                        <select class="custom-select" id="inputGroupSelect04" name="rooms" required>
                            <option selected>Choose...</option>
                            <option value="1">1 Room</option>
                            <option value="2">2 Rooms</option>
                            <option value="3">3 Rooms</option>
                            <option value="4">4 Rooms</option>
                            <option value="5">5 Rooms</option>
                        </select>
                        <div class="input-group-append">
                            <button class="btn btn-secondary" type="submit">Book</button>
                        </div>
                    </div>
                </form>
                <div>
                    <form method="POST" action="{{ route('random') }}">
                        @csrf
                        <button class="btn btn-primary" type="submit">
                            <i class="fa fa-btn fa-random"></i>
                            Random</button>
                    </form>
                    <form method="POST" action="{{ route('reset') }}">
                        @csrf
                        <button class="btn btn-dark" type="submit">
                            <i class="fa fa-btn fa-trash"></i>
                            Reset</button>
                    </form>
                </div>

            </div>

            <div class="bottom">
                <h4>Rooms Availability</h4>
                <hr>
                <div class="room-container">
                    @for ($floor = 1; $floor <= 10; $floor++)
                        <div class="floor">
                            <h5>Floor {{ $floor }}</h5>
                            @foreach ($rooms->where('floor', $floor) as $room)
                                <div class="room {{ $roomStatus($room->room_number, $room->is_booked) }}">
                                    {{ $room->room_number }}
                                </div>
                            @endforeach
                        </div>
                    @endfor
                </div>
            </div>
        </div>
        <div class="right">
            <h4>Status</h4>
            <div class="room-info">
                <div>
                    <span class="room available">101</span> &nbsp; Room available
                </div>
                <div>
                    <span class="room booked">102</span> &nbsp; Room Booked
                </div>
                <div>
                    <span class="room selected">103</span> &nbsp; Room Selected
                </div>
            </div>
            <hr>
            <h6>Built by</h6>
            <div class="user-profile">
                <img src="http://avatars.githubusercontent.com/u/64343449?v=4" alt="">
                <div>
                    <h6>Harsh Vishwakarma</h6>
                    <span>Laravel Artisan</span>
                </div>
            </div>
            <div class="user-socials">
                <a href="https://github.com/meinhoonharsh" target="_blank">
                    <i class="fa fa-github"></i> Github
                </a>
                <a href="https://www.linkedin.com/in/meinhoonharsh/" target="_blank">
                    <i class="fa fa-linkedin"></i> LinkedIn
                </a>

            </div>
        </div>
    </div>

    {{-- Add SweetAlert --}}
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        @if(session('success'))
            swal("Success", "{{ session('success') }}", "success", {
                button: null
            });
        @endif
        @if(session('error'))
            swal("Error", "{{ session('error') }}", "error", {
                button: null
            });
        @endif
    </script>

</body>

</html>
