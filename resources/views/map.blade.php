@extends('layouts.app')

@section('content')
    <div class="container">
        <div id="home" style="width: 4096px; height: 2048px; position: absolute;">
            <canvas id="myCanvas" class="mx-3 my-5" width="4096" height="2048" style="position: absolute;">
                <script>
                    var c = document.getElementById("myCanvas");
                    var ctx = c.getContext("2d");
                    ctx.lineWidth = 10;
                    ctx.lineCap = "round";
                    ctx.lineJoin = "round";
                    ctx.moveTo(215, 0);
                    ctx.lineTo(215, 135);
                    ctx.lineTo(1595, 135);
                    ctx.lineTo(1595, 1035);
                    ctx.stroke();
                </script>
            </canvas>

            <div class="">
                @foreach($homes as $key => $item)
                    <div class="modal fade" id="exampleModal-{{$item->name}}" tabindex="-1" aria-labelledby="exampleModalLabel"
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
                                    <p>Họ Tên: Dương Quang Định</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                    <button type="button" class="btn btn-success">Chỉ đường <i class="far fa-directions"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
            <div id="area" class="mx-3 my-5" style="position: absolute; width: 4096px; height: 2048px;">
                @foreach($homes as $key => $item)
                <div class="home-{{$item->name}}">
                    <input class="col-xs-1 py-5 mr-5 mb-5 btn btn-success" style="width: {{$item->width}}px; height: {{$item->height}}px;" type="button"
                           name="name" value="{{$item->name}}"
                           title="DUONG QUANG DINH | 19/05/2000"
                           data-toggle="modal" data-target="#exampleModal-{{$item->name}}"/>
                </div>
                @endforeach

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

                    z =  z + event.deltaY * -0.00025;
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
    </div>
@endsection


