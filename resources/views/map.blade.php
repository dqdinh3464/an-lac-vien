@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="modal-area">
            @foreach($homes as $item)
                @php
                    $owner =  getOwner($item->id_owner);
                @endphp
                <div class="modal fade" id="exampleModal-{{$item->name}}" tabindex="-1"
                     aria-labelledby="exampleModalLabel"
                     aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Thông tin căn hộ {{$item->name}}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Chủ sở hữu: {{$owner->name}}</p>
                                <p>Ngày sinh: {{$owner->date_of_birth}}</p>
                                <p>Số điện thoại: {{$owner->phonenumber}}</p>
                                <p>Email: {{$owner->email}}</p>
                                <p>Địa chỉ: {{$owner->address}}</p>
                                <p>Loại nhà: <strong>{{getHomeType($item->type_of_home)->name}}</strong></p>
                                <p>Tình Trạng: <strong>{{$item->status}}</strong></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                <button type="button" class="btn btn-success"
                                        onclick="drawWay({{$item->x}}, {{$item->y}});">
                                    Chỉ đường <i class="far fa-directions"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div id="home" style="width: {{(setting('home.home_x') + 1)*200 + 30*setting('home.home_x')}}px; height: {{setting('home.home_y')*120 + 30*setting('home.home_y')}}px; position: absolute;">
            <div class="ml-3">
                <input type="button" class="col-xs-2 py-5 mr-5 btn-1 btn-secondary" style="width: 430px;height: 120px;"
                       value="Phòng bảo vệ">
            </div>

            <div class="scrollable-bar"
                 style="width: {{(setting('home.home_x') + 1)*200 + 30*setting('home.home_x')}}px; height: {{setting('home.home_y')*120 + 30*setting('home.home_y')}}px; overflow: auto;">
                <canvas id="myCanvas" class="mx-3" width="{{(setting('home.home_x') + 1)*200 + 30*setting('home.home_x')}}" height="{{setting('home.home_y')*120 + 30*setting('home.home_y')}}"
                        style="position: absolute;">
                    <script>
                        var c = document.getElementById("myCanvas");
                        var ctx = c.getContext("2d");
                        ctx.lineWidth = 10;
                        ctx.lineCap = "round";
                        ctx.lineJoin = "round";
                        ctx.beginPath();

                        function drawWay(x, y) {
                            // ctx.clearRect(0,0,6180,3900);
                            ctx.moveTo(215, 0);
                            ctx.lineTo(215, 15);

                            var xx = y == 1 ? 200 + 200 * (x - 1) + 30 * (x - 1) - 100 : 230 + 200 * (x - 1) + 30 * (x - 2) - 100;
                            var yy = 15 + 120 * (y - 1) + 30 * (y - 1);

                            ctx.lineTo(215, yy);
                            ctx.lineTo(xx, yy);
                            ctx.lineTo(xx, yy + 15);

                            ctx.stroke();

                            return;
                        }
                    </script>
                </canvas>
                <div id="area" class="mx-3 my-5" style="position: absolute;">
                    @php
                        //dd(getRow(1));
                        $x = setting('home.home_x');
                        $y = setting('home.home_y');
                    @endphp
                    @for($i = 1; $i <= $y; $i++)
                        @php
                            $items = getRow($i);
                        @endphp
{{--                        @for($j = 0; $j < $x; $j++)--}}
                        @foreach($items as $item)
                            @php
                                $owner =  getOwner($item['id_owner']);
                                $type = getHomeType($item['type_of_home']);
                            @endphp
                            <input
                                class="delete-css py-5 mr-5 mb-5 btn-1 {{$type['background_color']}} home{{$item['id_owner']}}"
                                style="width: {{$item['width']}}px; height: {{$item['height']}}px;" type="button"
                                name="name" value="{{$item['name']}}"
                                title="{{$owner['name']." | ".date('d-m-Y', strtotime($owner['date_of_birth']))." | ".$owner['phonenumber']." | ".$owner['email']}}"
                                data-toggle="modal" data-target="#exampleModal-{{$item['name']}}"/>
{{--                        @endfor--}}
                        @endforeach
                        <br>
                    @endfor
                </div>
            </div>

        </div>

        <div class="d-flex flex-column justify-content-around" style="position: fixed; right: 5px; bottom: 5px;">
            <!--    <a class="btn zoom-in bg-white" style="border: solid 1px;" onclick="zoomIn()"><i class="fas fa-search-plus"></i></a>-->
            <!--    <a class="btn zoom-out bg-white" style="border: solid 1px;" onclick="zoomOut()"><i-->
            <!--            class="fas fa-search-minus"></i></a>-->
            <a class="btn zoom-init bg-white" style="border: solid 1px;" onclick="zoomReset()"><i
                    class="fas fa-recycle"></i></a>
        </div>

        <div class="bg-white mt-4" style="border: solid 1px; right: 5px; position: fixed;">
            <div class="px-4">
                <h4 class="text-center">CHÚ GIẢI</h4>
                <div class="details">
                    <div class="d-flex mb-3">
                        <div class="btn btn-success mr-3 w-60"></div>
                        <h5>Bãi đất trống</h5>
                    </div>

                    <div class="d-flex mb-3">
                        <div class="btn btn-warning mr-3 w-60"></div>
                        <h5>Nhà chưa có người ở</h5>
                    </div>

                    <div class="d-flex mb-3">
                        <div class="btn btn-danger mr-3 w-60"></div>
                        <h5>Nhà có người ở</h5>
                    </div>

                    <div class="d-flex mb-3">
                        <div class="btn btn-secondary mr-3 w-60"></div>
                        <h5>Phòng bảo vệ</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="search-container shadow-sm" style="position: fixed; left: 43%;">
            {{--                <form action="{{route("search")}}" method="POST">--}}
            {{--                    @csrf--}}
            <input type="text" class="p-2" name="search" id="search" style="font-size: 17px; border: none;"
                   placeholder="Tìm kiếm..." list="browsers">
            <datalist id="browsers">
                @foreach($owners as $item)
                    <option value="{{$item->id}}">{{$item->name}}</option>
                @endforeach
            </datalist>

            <button type="submit" onclick="addCss()" onkeypress="addCss()"
            " class="btn mb-0" style="border-radius: 0px;"><i class="far fa-search"></i>
            </button>
            {{--                </form>--}}
        </div>
    </div>
@endsection

@section("js")
    <script>
        let z = 1;

        const elem3 = document.querySelector('#home');
        // const elem1 = document.querySelector('#area');
        // const elem2 = document.querySelector('#myCanvas');

        // elem1.onwheel = zoom;
        // elem2.onwheel = zoom;
        elem3.onwheel = zoom;

        // elem1.addEventListener('wheel', zoom);
        // elem2.addEventListener('wheel', zoom);
        elem3.addEventListener('wheel', zoom);

        function zoom(event) {
            event.preventDefault();

            z = z + event.deltaY * -0.00025;
            z = Math.min(Math.max(0.2, z), 1);

            // elem1.style.zoom = z;
            // elem2.style.zoom = z;
            elem3.style.zoom = z;
        }

        function zoomIn() {
            $(document).ready(function () {
                var z = parseFloat($('#area').css('zoom'));

                $('.zoom-in').on('click', function () {
                    z = z + 0.1;

                    if (z < 1.0) {
                        // $('#area').css('zoom', z.toString());
                        // $('#myCanvas').css('zoom', z.toString());
                        $('#home').css('zoom', z.toString());
                    } else {
                        // $('#area').css('zoom', 1.0.toString());
                        // $('#myCanvas').css('zoom', 1.0.toString());
                        $('#home').css('zoom', 1.0.toString());
                    }
                });
            });
        }

        function zoomOut() {
            $(document).ready(function () {
                var z = parseFloat($('#area').css('zoom'));

                $('.zoom-out').on('click', function () {
                    z = z - 0.1;

                    if (z > 0.2) {
                        // $('#area').css('zoom', z.toString());
                        // $('#myCanvas').css('zoom', z.toString());
                        $('#home').css('zoom', z.toString());
                    } else {
                        // $('#area').css('zoom', 0.2.toString());
                        // $('#myCanvas').css('zoom', 0.2.toString());
                        $('#home').css('zoom', 0.2.toString());
                    }
                });
            });
        }

        function zoomReset() {
            $('.zoom-init').on('click', function () {
                // $('#area').css('zoom', '1.0');
                // $('#myCanvas').css('zoom', '1.0');
                $('#home').css('zoom', '1.0');
            });
        }

        // document.addEventListener('DOMContentLoaded', function() {
        //     const elem = document.getElementById('home');
        //     elem.style.cursor = 'move';
        //
        //     let pos = { top: 0, left: 0, x: 0, y: 0 };
        //
        //     const mouseDownHandler = function(e) {
        //         elem.style.cursor = 'move';
        //         elem.style.userSelect = 'none';
        //
        //         pos = {
        //             left: elem.scrollLeft,
        //             top: elem.scrollTop,
        //             // Get the current mouse position
        //             x: e.clientX,
        //             y: e.clientY,
        //         };
        //
        //         document.addEventListener('mousemove', mouseMoveHandler);
        //         document.addEventListener('mouseup', mouseUpHandler);
        //     };
        //
        //     const mouseMoveHandler = function(e) {
        //         // How far the mouse has been moved
        //         const dx = e.clientX - pos.x;
        //         const dy = e.clientY - pos.y;
        //
        //         // Scroll the element
        //         elem.scrollTop = pos.top - dy;
        //         elem.scrollLeft = pos.left - dx;
        //     };
        //
        //     const mouseUpHandler = function() {
        //         elem.style.cursor = 'move';
        //         elem.style.removeProperty('user-select');
        //
        //         document.removeEventListener('mousemove', mouseMoveHandler);
        //         document.removeEventListener('mouseup', mouseUpHandler);
        //     };
        //
        //     // Attach the handler
        //     elem.addEventListener('mousedown', mouseDownHandler);
        // });
    </script>

    <script>
        function addCss() {
            $('.delete-css').css({"border": "none"});
            var id = document.getElementById('search').value;
            $('.home' + id).css({"border": "5px solid black"});
        }
    </script>
@endsection


