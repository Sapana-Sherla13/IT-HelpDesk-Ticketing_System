<?php

function addNotification($conn, $userId, $ticketId, $message)
{
    $sql = "
    INSERT INTO notifications
    (UserId, TicketId, Message)
    VALUES
    ('$userId','$ticketId','$message')
    ";

    mysqli_query($conn,$sql);
}