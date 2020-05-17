<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 30/12/2019
 * Time: 11:28 PM
 */
//            if(payload.data.type == "message")
//            {
//
//                if(payload.data.sender_id == user_id)
//                {
//                    $('.direct-chat-messages').append('<div class="direct-chat-msg right">\n' +
//                        '                        <div class="direct-chat-info clearfix">\n' +
//                        '                            <span class="direct-chat-name pull-right">'+payload.data.sender_name+'</span>\n' +
//                        '                            <span class="direct-chat-timestamp pull-left">23 Jan 2:05 pm</span>\n' +
//                        '                        </div>\n' +
//                        '                        <!-- /.direct-chat-info -->\n' +
//                        '                        <img class="direct-chat-img" src="public/images/1562237299.jpg" alt="Message User Image"><!-- /.direct-chat-img -->\n' +
//                        '                        <div class="direct-chat-text">\n' + payload.data.body +
//                        '                        </div>\n' +
//                        '                        <!-- /.direct-chat-text -->\n' +
//                        '                    </div>');
//                }
//                 if(payload.data.sender_id != user_id)
//                {
//                    $('.direct-chat-messages').append('<div class="direct-chat-msg">\n' +
//                        '                        <div class="direct-chat-info clearfix">\n' +
//                        '                            <span class="direct-chat-name pull-right">'+payload.data.receiver_name+'</span>\n' +
//                        '                            <span class="direct-chat-timestamp pull-left">23 Jan 2:05 pm</span>\n' +
//                        '                        </div>\n' +
//                        '                        <!-- /.direct-chat-info -->\n' +
//                        '                        <img class="direct-chat-img" src="public/images/1562237299.jpg" alt="Message User Image"><!-- /.direct-chat-img -->\n' +
//                        '                        <div class="direct-chat-text">\n' + payload.data.body+
//                        '                        </div>\n' +
//                        '                        <!-- /.direct-chat-text -->\n' +
//                        '                    </div>');
//                    notificationTitle=payload.data.title;
//                    notificationOptions={
//                        body : payload.data.body,
//                        icon : payload.data.icon
//                    };
//                }
//            }
//            else{
//                notificationTitle=payload.data.title;
//                notificationOptions={
//                    body : payload.data.body,
//                    icon : payload.data.icon
//                };
//            }