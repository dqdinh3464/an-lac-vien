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
                                @auth
                                    <p>Chủ sở hữu: {{$owner->name}}</p>
                                    <p>Ngày sinh: {{$owner->date_of_birth}}</p>
                                    <p>Số điện thoại: {{$owner->phonenumber}}</p>
                                    <p>Email: {{$owner->email}}</p>
                                    <p>Địa chỉ: {{$owner->address}}</p>
                                @endauth
                                <p>Loại nhà: <strong>{{getHomeType($item->type_of_home)->name}}</strong></p>
                                <p>Tình Trạng: <strong>{{$item->status}}</strong></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                <button type="button" class="btn btn-success btn-canvas" data-dismiss="modal"
                                        onclick="drawWay({{$item->x}}, {{$item->y}})">
                                    Chỉ đường <i class="far fa-directions"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div id="home"
             style="width: {{(setting('home.home_x') + 1)*200 + 30*setting('home.home_x')}}px; height: {{setting('home.home_y')*120 + 30*setting('home.home_y')}}px; position: absolute;">
            <div class="ml-3">
                <input type="button" class="col-xs-2 py-5 mr-5 btn-1 btn-secondary" style="width: 430px;height: 120px;"
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
                        //dd(getRow(1));
                        $x = setting('home.home_x');
                        $y = setting('home.home_y');
                    @endphp
                    @for($i = 1; $i <= $y; $i++)
                        @php
                            $items = getRow($i);
                        @endphp
                        @foreach($items as $item)
                            @php
                                $owner =  getOwner($item['id_owner']);
                                $type = getHomeType($item['type_of_home']);
                            @endphp
                            <input
                                class="search-point{{$item['id_owner']}} delete-css py-5 mr-5 mb-5 btn-1 {{$type['background_color']}} home{{$item['id_owner']}}"
                                style="width: {{$item['width']}}px; height: {{$item['height']}}px;" type="button"
                                name="name" value="{{$item['name']}}"
                                title="{{$owner['name']." | ".date('d-m-Y', strtotime($owner['date_of_birth']))." | ".$owner['phonenumber']." | ".$owner['email']}}"
                                data-toggle="modal" data-target="#exampleModal-{{$item['name']}}"/>
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
            <input type="text" class="p-2" name="search" id="search" style="font-size: 17px; border: none;"
                   placeholder="Tìm kiếm..." list="browsers">
            <datalist id="browsers">
                @foreach($owners as $item)
                    <option id="{{$item->id}}" value="{{$item->id}}">{{$item->name}}</option>
                @endforeach
            </datalist>

            <button type="submit" onclick="addCss()" class="btn btn-secondary mb-0" style="border-radius: 0px;">
                <i class="far fa-search"></i>
            </button>
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
            var listStyle = document.getElementsByTagName("style")[0];
            if(listStyle.length != 0){
                listStyle.removeChild(listStyle.childNodes[0]);
            }
            console.log(listStyle);

            var id = document.getElementById('search').value; //get id của người cần tìm
            // var styleElem = document.createElement('style'); //tạo thẻ style
            var name = "searchpoint"; //đặt tên cho animation
            var value = "0% {background: #fff;}\n100% {background: #daf04e;}"; //set giá trị @keyframes

            var textNode = document.createTextNode("" +
                ".search-point" + id + "{\n" + "position: relative;\n" + "animation: searchpoint 0.7s infinite;\n}\n" +
                "@keyframes " + name + "{\n" + value + "\n}"
            );

            console.log(textNode);
            // styleElem.appendChild(textNode);
            listStyle.appendChild(textNode);
            // document.head.appendChild(styleElem);
            document.head.appendChild(listStyle);

            // if (CSS && CSS.supports && CSS.supports('animation: name')) {
            //     addKeyFrames = function(name, value) {
            //         // var pos = myReuseableStylesheet.length;
            //         myReuseableStylesheet.insertRule("@keyframes " + name + "{" + value + "}", pos);
            //     }
            //     console.log("111");
            // } else {
            //     addKeyFrames = function (name, value) {
            //         var str = name + "{" + value + "}";
            //         // var pos = myReuseableStylesheet.length;
            //         myReuseableStylesheet.insertRule("@-webkit-keyframes " + str, pos);
            //         myReuseableStylesheet.insertRule("@keyframes " + str, pos + 1);
            //     }
            // }

            // var id = document.getElementById('search').value;
            // console.log(id);
            //
            //     var arrayJson =
            //     {
            //         '0%' :  {'background': '#fff'}
            //         '100%' : {'background': '#f0ad4e'}
            //     }
            //
            //     $.keyframe.define(
            //         [
            //             $.extend({ name: 'searchpoint' }, arrayJson)
            //         ]
            //     );
            //
            //     $('.home' + id).playKeyframe({
            //         name:'searchpoint',
            //         duration:"0.7s",
            //         timingFunction:'ease',
            //         iterationCount:'100',
            //         direction:'normal',
            //         fillMode:'forwards',
            //         complete: increment
            //     });
        }
    </script>
@endsection


