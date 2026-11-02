<?php
// -----------------------------------------------------------------------------
//  Template Name: ICS Generator
//  Page: ICS CALENDAR INVITE GENERATOR (.ics)
//  Base Theme: International Social Housing Festival (ISHF) 2027 Lisbon
//  Edited: BB, 27-OUT-2026
// -----------------------------------------------------------------------------

if (!class_exists('ICS')) {
  class ICS
  {
    const DT_FORMAT = 'Ymd\THis\e\Europe/Lisbon';

    protected $properties = array();
    private $available_properties = array(
      'description',
      'dtend',
      'dtstart',
      'location',
      'summary',
      'trigger',
      'url',
    );

    public function __construct($props)
    {
      $this->set($props);
    }

    public function set($key, $val = false)
    {
      if (is_array($key)) {
        foreach ($key as $k => $v) {
          $this->set($k, $v);
        }
      } else {
        if (in_array($key, $this->available_properties)) {
          $this->properties[$key] = $this->sanitize_val($val, $key);
        }
      }
    }

    public function to_string()
    {
      $rows = $this->build_props();
      return implode("\r\n", $rows);
    }

    private function build_props()
    {
      $ics_props = array(
        'BEGIN:VCALENDAR',
        'VERSION:2.0',
        'PRODID:-//ishf2027.com//ISHF 2027 Lisbon iCal Generator v1.0//EN',
        'CALSCALE:GREGORIAN',
        'BEGIN:VEVENT'
      );

      $props = array();
      foreach ($this->properties as $k => $v) {
        $props[strtoupper($k . ($k === 'url' ? ';VALUE=URI' : ''))] = $v;
      }

      $props['DTSTAMP'] = $this->format_timestamp('now');
      $props['UID'] = uniqid('ishf2027_', true);

      foreach ($props as $k => $v) {
        $ics_props[] = "$k:$v";
      }

      $ics_props[] = 'END:VEVENT';
      $ics_props[] = 'END:VCALENDAR';

      return $ics_props;
    }

    private function sanitize_val($val, $key = false)
    {
      switch ($key) {
        case 'dtend':
        case 'dtstamp':
        case 'dtstart':
          $val = $this->format_timestamp($val);
          break;
        default:
          $val = $this->escape_string($val);
      }
      return $val;
    }

    private function format_timestamp($timestamp)
    {
      $dt = new DateTime($timestamp);
      return $dt->format(self::DT_FORMAT);
    }

    private function escape_string($str)
    {
      return preg_replace('/([\,;])/', '\\\$1', (string) $str);
    }
  }
}

header('Content-Type: text/calendar; charset=utf-8');
header('Content-Disposition: attachment; filename=ishf-2027-invite.ics');

// -- Get parameters from query vars
$start       = sanitize_text_field(get_query_var('ics_start'));
$end         = sanitize_text_field(get_query_var('ics_end'));
$name        = sanitize_text_field(get_query_var('ics_name'));
$description = sanitize_text_field(get_query_var('ics_description'));
$location    = sanitize_text_field(get_query_var('ics_location'));
$alert       = sanitize_text_field(get_query_var('ics_alert'));
$url         = sanitize_text_field(get_query_var('ics_url'));

// Default fallback to ISHF 2027 dates if none provided
if (empty($start) || empty($name)) {
  $start       = '2027-06-07 09:00:00';
  $end         = '2027-06-09 18:00:00';
  $name        = 'ISHF 2027 Lisbon - International Social Housing Festival';
  $description = 'Cities with Soul: It Begins with a Key. 7-9 June 2027, Lisbon, Portugal.';
  $location    = 'Lisbon, Portugal';
}

$event = new ICS(array(
  'location'    => !empty($location) ? $location : 'Lisbon, Portugal',
  'description' => !empty($description) ? $description : '',
  'dtstart'     => $start,
  'dtend'       => !empty($end) ? $end : $start,
  'summary'     => $name,
  'trigger'     => !empty($alert) ? $alert : '60',
  'url'         => !empty($url) ? $url : home_url('/'),
));

echo $event->to_string();
