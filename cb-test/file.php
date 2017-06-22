$my_file = "somefile.zip";
$my_path = "/your_path/to_the_attachment/";
$my_name = "Olaf Lederer";
$my_mail = "my@mail.com";
$my_replyto = "my_reply_to@mail.net";
$my_subject = "This is a mail with attachment.";
$my_message = "Hallo,rndo you like this script? I hope it will help.rnrngr. Olaf";
mail_attachment($my_file, $my_path, "recipient@mail.org", $my_mail, $my_name, $my_replyto, $my_subject, $my_message);