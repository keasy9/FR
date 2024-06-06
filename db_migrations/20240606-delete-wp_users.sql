DELETE wp_users, wp_usermeta
FROM wp_users INNER JOIN wp_usermeta ON wp_users.ID = wp_usermeta.user_id
WHERE wp_usermeta.meta_key = 'wp_capabilities' AND
    wp_usermeta.meta_value NOT LIKE '%administrator%' AND
    wp_usermeta.meta_value NOT LIKE '%fr_moderator%'