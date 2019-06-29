<?php 

class User {

    public const  LANGUAGES = [
        "Afrikanns",
        "Albanian",
        "Arabic",
        "Armenian",
        "Azerbaijanian",
        "Basque",
        "Bengali",
        "Bulgarian",
        "Catalan",
        "Cambodian",
        "Chinese (Mandarin)",
        "Croation",
        "Czech",
        "Danish",
        "Dutch",
        "English",
        "Estonian",
        "Fiji",
        "Finnish",
        "French",
        "Georgian",
        "German",
        "Greek",
        "Gujarati",
        "Hebrew",
        "Hindi",
        "Hungarian",
        "Icelandic",
        "Indonesian",
        "Irish",
        "Italian",
        "Japanese",
        "Javanese",
        "Korean",
        "Latin",
        "Latvian",
        "Lithuanian",
        "Macedonian",
        "Malay",
        "Malayalam",
        "Maltese",
        "Maori",
        "Marathi",
        "Mongolian",
        "Nepali",
        "Norwegian",
        "Persian",
        "Polish",
        "Portuguese",
        "Punjabi",
        "Quechua",
        "Romanian",
        "Russian",
        "Samoan",
        "Serbian",
        "Slovak",
        "Slovenian",
        "Spanish",
        "Swahili",
        "Swedish ",
        "Tamil",
        "Tatar",
        "Telugu",
        "Thai",
        "Tibetan",
        "Tonga",
        "Turkish",
        "Ukranian",
        "Urdu",
        "Uzbek",
        "Vietnamese",
        "Welsh",
        "Xhosa"
    ];

    public const  YEARS = ['Currently studying', '2019', '2018', '2017', '2016', '2015', '2014', '2013', '2012', '2011'
        , '2010', '2009', '2008', '2007', '2006', '2005', '2004', '2003', '2002', '2001', '2000'
        , '1999', '1998', '1997', '1996', '1995', '1994', '1993', '1992', '1991', '1990'
        , '1989', '1988', '1987', '1986', '1985', '1984', '1983', '1982', '1981', '1980'];

    public const  GENDERS = ['na', 'male', 'female'];

    public const  EDU_LEVELS = ['High School', 'Associate´s Degree', 'Bachelor´s Degree', 'Master´s Degree', 'Doctor of Medicine (M.d.)', 'Doctor of Philosophy (Ph. D.)'];

    public const USER_STATUSES = [
        'Inactive',
        'Incomplete',
        'Complete',
        'Complete + CV',
    ];

    public const  WORKFIELD = [
        "Accounting & Finance",             
        "Banking & Finance Services",       
        "Education",                        
        "Engineering",                      
        "Healthcare",                       
        "Human Resources",                  
        "IT",                               
        "Legal",                            
        "Procurement",                      
        "Sales & Marketing",                
    ];

    public static function init() {
        add_action('wp_ajax_getAllUsers', [self::class, 'ajaxAllUsers']);
    }

    public static function ajaxAllUsers() {
        $users = get_users( array( 'fields' => array( 'display_name' ) ) );
        error_log('user: ' . print_r($users, true));
        if(!empty($users)) {
            return wp_send_json_success($users);
        } else {
            return wp_send_json_error('Not users');
        }
        
    }

    public static function currentIpCountry()
    {
        $real_ip_adress = false;
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $real_ip_adress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $real_ip_adress = $_SERVER['HTTP_CLIENT_IP'];
        } else if (isset($_SERVER['REMOTE_ADDR'])) {
            $real_ip_adress = $_SERVER['REMOTE_ADDR'];
        }
        if ($real_ip_adress) {
            $location = json_decode(file_get_contents('https://www.iplocate.io/api/lookup/' . $real_ip_adress));
            if (!empty($location) && empty($location->country)) {
                return 'LocalHost';
            } else if (!empty($location) && !empty($location->country)) {
                return $location->country;
            } else {
                return 'Not found';
            }
        }
        return false;
    }

    public static function getUserProfile($user_id)
    {
        $user = get_user_by('id', $user_id);
        if (empty($user))
            return null;

        // send out user profile ;)
        return [
            'id' => $user->ID,
            'display_name' => $user->display_name,
            'firstName' => $user->first_name,
            'lastName' => $user->last_name,
            'registered' => $user->user_registered,
            'email' => $user->user_email,
            'custom' => static::getMetadata('custom', $user_id),
        ];
    }

    public static function getMetadata($key, $user_id = null, $single = true)
    {
        if (empty($user_id) && !is_user_logged_in())
            return false;

            $value = get_user_meta($user_id ?? get_current_user_id(), Register::WP_CUSTOM . $key, $single);

        return !empty($value) ? $value : null;
    }

    public static function updateMetadata($key, $user_id = null, $value)
    {
        if (empty($user_id) && !is_user_logged_in())
            return false;

            $value = update_user_meta($user_id ?? get_current_user_id(), Register::WP_CUSTOM . $key, $value);

        return $value;
    }

    public static function getEmails($users) {
        foreach ($users as $user) {
            $email[] = $user->user_email;
        }

        return $email;
    }

    public static function getIds($users) {
        foreach ($users as $user) {
            $Ids[] = $user->ID;
        }

        return $Ids;
    }

    public static function getIdsFromMail($emails) {
        foreach ($emails as $email) {
            $id = get_user_by( 'email', $email)->ID;
            if(!empty($id)) {
                $ids[] = $id;
            }
        }
        return $ids;
    }

    public static function DaysFrom($date) {
        
        return sprintf( _x( '%s ago', '%s = human-readable time difference', 'wptool'),
                            human_time_diff( (new \Datetime($date))->getTimestamp() )
                        );

    }
    
    public static function getOptionFromConstant($constant) {
        $optionString = '';
        $constante = constant('self::'.$constant);
        if(!empty($constante)) {
            foreach($constante as $const) {
                $optionString .= "<option value='" . $const ."'>" . $const ."</option>";
            }
            return $optionString;
        } else {
            return null;
        }
        
    }

}