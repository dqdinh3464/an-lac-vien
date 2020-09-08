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

        <div id="home" style="width: {{27*200 + 30*26}}px; height: {{26*120 + 30*26}}px; position: absolute;">
            <div class="ml-3">
                <input type="button" class="col-xs-2 py-5 mr-5 btn-1 btn-secondary" style="width: 430px;height: 120px;"
                       value="Phòng bảo vệ">
            </div>

            <div class="scrollable-bar"
                 style="width: {{27*200 + 30*26}}px; height: {{26*120 + 30*26}}px; overflow: auto;">
                <canvas id="myCanvas" class="mx-3" width="{{27*200 + 30*26}}" height="{{26*120 + 30*26}}"
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
                    @foreach($homes as $key => $item)
                        @php
                            $owner =  getOwner($item->id_owner);
                            $type = getHomeType($item->type_of_home);
                        @endphp
                        <input class="py-5 mr-5 mb-5 btn-1 {{$type->background_color}}"
                               style="width: {{$item->width}}px; height: {{$item->height}}px;" type="button"
                               name="name" value="{{$item->name}}"
                               title="{{$owner->name." | ".date('d-m-Y', strtotime($owner->date_of_birth))." | ".$owner->phonenumber." | ".$owner->email}}"
                               data-toggle="modal" data-target="#exampleModal-{{$item->name}}"/>
                    @endforeach

                </div>
            </div>

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

        <div class="topnav" style="position: fixed;">
            <div class="search-container shadow-sm">
                <form action="{{route('search')}}" method="POST">
                    <input type="text" class="p-2" name="search" id="search" style="font-size: 17px; border: none;" placeholder="Tìm kiếm..." oninput="findHome()">
                    <button type="submit" class="btn mb-0" style="border-radius: 0px;"><i class="far fa-search"></i></button>
                </form>
            </div>
            <div id="ownerList">

            </div>
            {{ csrf_field() }}
        </div>

        <script>
            function findHome(){
                var query = document.getElementById("search").value; //lấy giá trị ng dùng gõ

                if(query != '') //kiểm tra khác rỗng thì thực hiện đoạn lệnh bên dưới
                {
                    var _token = $('input[name="_token"]').val(); // token để mã hóa dữ liệu

                    $.ajax({
                        url:"{{ route('search') }}", // đường dẫn khi gửi dữ liệu đi 'search' là tên route mình đặt bạn mở route lên xem là hiểu nó là cái j.
                        method:"POST", // phương thức gửi dữ liệu.
                        data:{query: query, _token:_token},
                        success:function(data){ //dữ liệu nhận về
                            $('#ownerList').fadeIn();
                            $('#ownerList').html(data); //nhận dữ liệu dạng html và gán vào cặp thẻ có id là ownerList
                        }
                    });
                }
            }
        </script>
    </div>
@endsection


