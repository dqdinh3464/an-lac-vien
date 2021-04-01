@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="modal-area" id="modal-area">

        </div>

        <div id="home"
             style="width: {{(setting('home.home_x') + 1)*200 + 30*setting('home.home_x')}}px; height: {{setting('home.home_y')*120 + 30*setting('home.home_y')}}px; position: absolute;">
            <div class="ml-3">
                <input type="button" class="col-xs-2 py-5 mr-5 btn-1 btn-secondary" style="width: 430px;height: 240px;"
                       value="Phòng bảo vệ">
            </div>

            <div class="scrollable-bar"
                 style="width: {{(setting('home.home_x') + 1)*200 + 30*setting('home.home_x')}}px; height: {{setting('home.home_y')*120 + 30*setting('home.home_y')}}px; overflow: auto;">
                <canvas id="myCanvas" class="mx-3"
                        width="{{(setting('home.home_x') + 1)*200 + 30*setting('home.home_x')}}"
                        height="{{setting('home.home_y')*120 + 30*setting('home.home_y')}}"
                        style="position: absolute;">
                    <script>
                        var c = document.getElementById("myCanvas");
                        var ctx = c.getContext("2d");
                        ctx.lineWidth = 10;
                        ctx.lineCap = "round";
                        ctx.lineJoin = "round";

                        function drawWay(x, y) {
                            ctx.clearRect(0, 0, $('canvas')[0].width, $('canvas')[0].height);
                            ctx.beginPath();

                            ctx.moveTo(215, 0);
                            ctx.lineTo(215, 15);

                            var xx = y == 1 ? 200 + 200 * (x - 1) + 30 * (x - 1) - 100 : 230 + 200 * (x - 1) + 30 * (x - 2) - 100;
                            var yy = 15 + 120 * (y - 1) + 30 * (y - 1);

                            ctx.lineTo(215, yy);
                            ctx.lineTo(xx, yy);
                            ctx.lineTo(xx, yy + 15);

                            ctx.stroke();
                        }
                    </script>
                </canvas>
                <div id="area" class="mx-3 my-5" style="position: absolute;">
                    @php
                        $x = setting('home.home_x');
                        $y = setting('home.home_y');
                    @endphp
                    @for($i = 1; $i <= $y; $i++)
                        @php
                            $items = getRow($i);
                        @endphp
                        @foreach($items as $item)
                            @php
                                $_item = json_encode($item);

                                $owner =  getOwner($item['id_owner']);
                                $_owner = json_encode($owner);

                                $type = getHomeType($item['type_of_home']);
                                $_type = json_encode($type);
                            @endphp
                            <input type="button"
                                class="search-point{{$owner['id']}} delete-css py-5 mr-5 mb-5 btn-1 {{$type['background_color']}} home{{$item['id_owner']}}"
                                style="width: {{$item['width']}}px; height: {{$item['height']}}px;"
                                value="{{$item['name']}}"
                                onclick="showInfo({{$_item}}, {{$_owner}}, {{$_type}})"
                                title="{{$owner['name']." | ".date('d-m-Y', strtotime($owner['date_of_birth']))." | ".$owner['phonenumber']." | ".$owner['email']}}"
                                data-toggle="modal" data-target="#exampleModal-{{$item['name']}}">
                        @endforeach
                        <br>
                    @endfor
                </div>
            </div>

        </div>

        <div class="d-flex flex-column justify-content-around" style="position: fixed; right: 5px; bottom: 5px;">
            <a class="btn zoom-in bg-white" style="border: solid 1px;" onclick="zoomIn()"><i class="fas fa-search-plus"></i></a>
            <a class="btn zoom-out bg-white" style="border: solid 1px;" onclick="zoomOut()"><i class="fas fa-search-minus"></i></a>
            <a class="btn zoom-init bg-white" style="border: solid 1px;" onclick="zoomReset()"><i class="fas fa-recycle"></i></a>
        </div>

        <div class="bg-white mt-4" style="border: solid 1px; right: 5px; position: fixed;">
            <div class="px-4">
                <h4 class="text-center">CHÚ GIẢI</h4>
                <div class="details">
                    @foreach($home_types as $item)
                        <div class="d-flex mb-3">
                            <div class="btn {{$item->background_color}} mr-3 w-60"></div>
                            <h5>{{$item->name}}</h5>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="search-container shadow-sm" style="position: fixed; left: 50%;">
            <input type="text" class="p-2" name="search" id="search" style="font-size: 17px; border: none;"
                   placeholder="Tìm kiếm..." list="browsers" required>
            <datalist id="browsers">
                @foreach($owners as $item)
                    <option id="{{$item->id}}" value="{{$item->name}}">
                @endforeach
            </datalist>

            <button type="submit" id="btn-search" class="btn btn-secondary mb-0" style="border-radius: 0px;">
                <i class="far fa-search"></i>
            </button>
        </div>
    </div>
@endsection

@section("js")
    <script>
        var z = 1.0;

        const elem = document.querySelector('#home');
        elem.onwheel = zoom;
        elem.addEventListener('wheel', zoom);

        //zoom bằng chuột
        function zoom(event) {
            event.preventDefault();

            z = z + event.deltaY * -0.00025;
            z = Math.min(Math.max(0.2, z), 1.0);

            elem.style.zoom = z;
        }

        //zoom to bằng nút;
        function zoomIn() {
            $('.zoom-in').on('click', function () {
                z = z + 0.1;
                console.log(z);

                if (z < 1.0) {
                    $('#home').css('zoom', z.toString());
                } else {
                    z = 1.0;
                    $('#home').css('zoom', z.toString());
                }
            });
        }

        //zoom nhỏ bằng nút;
        function zoomOut() {
            $('.zoom-out').on('click', function () {
                z = z - 0.1;
                console.log(z);

                if (z > 0.2) {
                    $('#home').css('zoom', z.toString());
                } else {
                    z = 0.2;
                    $('#home').css('zoom', z.toString());
                }
            });
        }

        //khôi phục lại kích thước zoom gốc
        function zoomReset() {
            $('.zoom-init').on('click', function () {
                z = 1.0;
                $('#home').css('zoom', z.toString());
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
        function showInfo(item, owner, type) {
            let s = (item.width/100)*(item.height/100);
            let width = item.width/100;
            let height = item.height/100;
            let price = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(item.price);
            if (item.type_of_home == 3){
                var content = "<div class=\"modal fade\" id=\"exampleModal-" + item.name + "\" tabindex=\"-1\"\n" +
                    "                             aria-labelledby=\"exampleModalLabel\"\n" +
                    "                             aria-hidden=\"true\">\n" +
                    "                            <div class=\"modal-dialog\">\n" +
                    "                                <div class=\"modal-content\">\n" +
                    "                                    <div class=\"modal-header\">\n" +
                    "                                        <h5 class=\"modal-title\" id=\"exampleModalLabel\">Thông tin ô đất " + item.name + "</h5>\n" +
                    "                                        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Đóng\">\n" +
                    "                                            <span aria-hidden=\"true\">&times;</span>\n" +
                    "                                        </button>\n" +
                    "                                    </div>\n" +
                    "                                    <div class=\"modal-body\">\n" +
                    "                                        <p>Họ Tên: " + owner.name + "</p>\n" +
                    "                                        <p>Ngày sinh: " + owner.date_of_birth + "</p>\n" +
                    "                                        <p>Ngày mất: " + owner.date_of_death + "</p>\n" +
                    "                                        @auth\n" +
                    "                                            <p>Số điện thoại người thân: " + owner.phonenumber + "</p>\n" +
                    "                                            <p>Email người thân: " + owner.email + "</p>\n" +
                    "                                        @endauth\n" +
                    "                                        <p>Địa chỉ người thân: " + owner.address + "</p>\n" +
                    "                                        <p>Loại ô đất: <strong>" + type.name + "</strong></p>\n" +
                    "                                        <p>Tình trạng: <strong>" + item.status + "</strong></p>\n" +
                    "                                        <p>Diện tích ô đất: <strong>" + s + "m2 (" + width + "m x " + height + "m)</strong></p>\n" +
                    "                                        <p>Giá ô đất: <strong>" + price + "</strong></p>\n" +
                    "                                    </div>\n" +
                    "                                    <div class=\"modal-footer\">\n" +
                    "                                        <button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">Đóng</button>\n" +
                    "                                        <button type=\"button\" class=\"btn btn-success btn-canvas\" data-dismiss=\"modal\"\n" +
                    "                                                onclick=\"drawWay(" + item.x + ", " + item.y + ")\">\n" +
                    "                                            Chỉ đường <i class=\"far fa-directions\"></i></button>\n" +
                    "                                    </div>\n" +
                    "                                </div>\n" +
                    "                            </div>\n" +
                    "                        </div>";
            }
            else{
                var content = "<div class=\"modal fade\" id=\"exampleModal-" + item.name + "\" tabindex=\"-1\"\n" +
                    "                             aria-labelledby=\"exampleModalLabel\"\n" +
                    "                             aria-hidden=\"true\">\n" +
                    "                            <div class=\"modal-dialog\">\n" +
                    "                                <div class=\"modal-content\">\n" +
                    "                                    <div class=\"modal-header\">\n" +
                    "                                        <h5 class=\"modal-title\" id=\"exampleModalLabel\">Thông tin ô đất " + item.name + "</h5>\n" +
                    "                                        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Đóng\">\n" +
                    "                                            <span aria-hidden=\"true\">&times;</span>\n" +
                    "                                        </button>\n" +
                    "                                    </div>\n" +
                    "                                    <div class=\"modal-body\">\n" +
                    "                                        <p>Họ Tên: " + owner.name + "</p>\n" +
                    "                                        <p>Ngày sinh: " + owner.date_of_birth + "</p>\n" +
                    "                                        <p>Số điện thoại: " + owner.phonenumber + "</p>\n" +
                    "                                        <p>Email: " + owner.email + "</p>\n" +
                    "                                        <p>Địa chỉ: " + owner.address + "</p>\n" +
                    "                                        <p>Loại ô đất: <strong>" + type.name + "</strong></p>\n" +
                    "                                        <p>Tình trạng: <strong>" + item.status + "</strong></p>\n" +
                    "                                        <p>Diện tích ô đất: <strong>" + s + "m2 (" + width + "m x " + height + "m)</strong></p>\n" +
                    "                                        <p>Giá ô đất: <strong>" + price + "</strong></p>\n" +
                    "                                    </div>\n" +
                    "                                    <div class=\"modal-footer\">\n" +
                    "                                        <button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">Đóng</button>\n" +
                    "                                        <button type=\"button\" class=\"btn btn-success btn-canvas\" data-dismiss=\"modal\"\n" +
                    "                                                onclick=\"drawWay(" + item.x + ", " + item.y + ")\">\n" +
                    "                                            Chỉ đường <i class=\"far fa-directions\"></i></button>\n" +
                    "                                    </div>\n" +
                    "                                </div>\n" +
                    "                            </div>\n" +
                    "                        </div>";
            }

            document.getElementById('modal-area').innerHTML = content;
        }

        $('#btn-search').on('click',function(){
            $value = $('#search').val();
            $.ajax({
                type: 'get',
                url: '{{ URL::to('search') }}',
                data: {
                    'search': $value
                },
                success:function(data){
                    $("input").removeClass("search-point");
                    for(var i = 0; i < data.length; i++){
                        $(".search-point" + data[i]).addClass("search-point");
                    }

                    //zoom nhỏ lại
                    z = 0.2;
                    $('#home').css('zoom', z.toString());
                }
            });
        })
        $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
    </script>
@endsection
