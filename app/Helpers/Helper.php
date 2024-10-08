<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Mail;
use App\Mail\SendMail;

use DateTime;
use DateTimeZone;

class Helper
{
    const TIME_ZONE = 'Asia/Jakarta';
    /**
     * Apply ordering to the given query based on the specified order parameter.
     *
     * This function accepts a query builder instance and an order parameter.
     * The order parameter can specify the column name and direction for sorting.
     * A descending order is indicated by a prefix of '-' before the column name.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The query builder instance.
     * @param string|null $order The order parameter that defines the sorting column and direction.
     *
     * @return \Illuminate\Database\Eloquent\Builder Returns the modified query with the applied order.
     */
    public static function order($query, $order)
    {
        if ($order) {
            $direction = 'asc'; // Default direction
            if (str_starts_with($order, '-')) {
                $direction = 'desc'; // Set direction to descending if prefixed with '-'
                $order = ltrim($order, '-'); // Remove '-' prefix
            }

            $query->orderBy($order, $direction);
        }

        return $query;
    }


    /**
     * Eager load relationships for a given query.
     *
     * This function accepts a query and a string of entity names, 
     * and attempts to eager load the specified relationships. 
     * The entity names should be comma-separated and can include 
     * spaces, which will be removed during processing.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The query builder instance.
     * @param string|null $entities A comma-separated string of entity names to eager load.
     *
     * @return \Illuminate\Database\Eloquent\Builder|null Returns the modified query with eager loaded entities, 
     * or null if no entities are specified. In case of an error during the eager loading process,
     * it returns a JSON exception response with validation errors.
     *
     * @throws \Throwable Throws any exception encountered during the eager loading process.
     */
    public static function entities($query, $entities)
    {
        if ($entities != null || $entities != '') {
            $entities = str_replace(' ', '', $entities);
            $entities = explode(',', $entities);

            try {
                return $query = $query->with($entities);
            } catch (\Throwable $th) {
                return Json::exception(null, validator()->errors());
            }
        }
    }


    /**
     * Apply a limit to the given query based on the specified limit parameter.
     *
     * This function accepts a query builder instance and a limit parameter.
     * If the limit parameter is a positive integer, it applies the limit to the query.
     * The function also ensures that the limit is a valid number before applying it.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The query builder instance.
     * @param int|null $limit The maximum number of records to return.
     *
     * @return \Illuminate\Database\Eloquent\Builder Returns the modified query with the applied limit.
     *
     * @throws \InvalidArgumentException If the limit is not a positive integer.
     */
    public static function limit($query, $limit)
    {
        if ($limit !== null) {
            if (is_numeric($limit) && (int)$limit > 0) {
                $query->limit((int)$limit);
            } else {
                throw new \InvalidArgumentException('Limit must be a positive integer.');
            }
        }

        return $query;
    }


    /**
     * Apply search criteria to the given query based on the specified search term.
     *
     * This function accepts a query builder instance, a search term, and the field(s) 
     * to search against. It can perform exact matches, partial matches, and 
     * case-insensitive searches, depending on the specified search type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The query builder instance.
     * @param string|null $q The search term to filter records.
     * @param string|array $records The field(s) to search against, can be a single field or an array of fields.
     * @param string $type The type of search to perform (e.g., 'exact', 'partial', 'like').
     *
     * @return \Illuminate\Database\Eloquent\Builder Returns the modified query with the applied search criteria.
     *
     * @throws \InvalidArgumentException If the search type is invalid.
     */
    public static function search($query, $q, $records = 'name', $type = 'like')
    {
        if ($q) {
            $records = is_array($records) ? $records : [$records];

            foreach ($records as $record) {
                switch ($type) {
                    case 'exact':
                        $query->where($record, $q);
                        break;

                    case 'partial':
                    case 'like':
                        $query->orWhere($record, 'LIKE', '%' . $q . '%');
                        break;

                    case 'caseInsensitive':
                        $query->orWhereRaw('LOWER(' . $record . ') LIKE ?', [strtolower('%' . $q . '%')]);
                        break;

                    default:
                        throw new \InvalidArgumentException('Invalid search type provided.');
                }
            }
        }

        return $query;
    }

    /**
     * Generate a unique slug based on the given title.
     *
     * This function creates a URL-friendly slug from the provided title and ensures
     * that the slug is unique within the specified query. If a slug already exists,
     * it appends a numeric suffix (e.g., '-1', '-2') to create a unique slug.
     *
     * @param \Illuminate\Database\Eloquent\Builder $q The query builder instance for checking existing slugs.
     * @param string $title The title to generate a slug from.
     *
     * @return string Returns a unique slug.
     */
    public static function generatedSlug($q, $title)
    {
        // Generate the initial slug from the title
        $new_slug = Str::slug($title);
        // Check if the slug already exists in the database
        $slug_check = $q->where('slug', $new_slug)->count();

        if ($slug_check == 0) {
            $slug = $new_slug; // Use the new slug if it's unique
        } else {
            $increment = 1; // Start incrementing from 1
            // Loop until a unique slug is found
            while ($q->where('slug', $new_slug . '-' . $increment)->exists()) {
                $increment++;
            }
            $slug = $new_slug . '-' . $increment; // Append the increment to the slug
        }

        return $slug; // Return the unique slug
    }


    /**
     * Apply various where conditions to the given query dynamically.
     *
     * This function allows applying different types of where conditions, including 
     * whereHas, whereIn, and standard where clauses based on the provided parameters.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The query builder instance.
     * @param string|null $type The type of condition to apply ('has', 'in', or 'basic').
     * @param mixed $condition The condition or array of conditions to apply.
     * @param mixed $values Optional values to use with the condition.
     *
     * @return \Illuminate\Database\Eloquent\Builder Returns the modified query with applied conditions.
     *
     * @throws \InvalidArgumentException If an invalid type is provided.
     */
    public static function dynamicWhere($query, $type, $condition, $values = null)
    {
        switch ($type) {
            case 'has':
                if ($condition && $values) {
                    $query->whereHas($condition, function ($queryBuilder) use ($values) {
                        foreach ($values as $field => $target) {
                            $queryBuilder->where($target, $field);
                        }
                    });
                }
                break;

            case 'in':
                if ($condition && $values) {
                    $query->whereIn($condition, $values);
                }
                break;

            case 'basic':
                if ($condition && $values) {
                    $query->where($condition, $values);
                }
                break;

            default:
                throw new \InvalidArgumentException('Invalid type provided for dynamic where condition.');
        }

        return $query;
    }


    /**
     * Retrieve the ID of the authenticated user from the request.
     *
     * This function checks if a request object is provided and returns the 
     * ID of the authenticated user. If no user is authenticated or the 
     * request is null, it returns null.
     *
     * @param \Illuminate\Http\Request|null $request The HTTP request instance.
     * 
     * @return int|null Returns the user ID or null if not authenticated.
     */
    public static function getterUserId($request)
    {
        return $request && $request->user() ? $request->user()->id : null;
    }

    /**
     * Filter the query results by a specified date range.
     *
     * This function applies a whereBetween clause to the given query, 
     * filtering results based on the specified date range for a target field.
     * If any of the parameters are invalid, the query remains unmodified.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The query builder instance.
     * @param string $targetField The name of the field to filter on.
     * @param string $since The start date for the range (inclusive).
     * @param string $until The end date for the range (inclusive).
     *
     * @return \Illuminate\Database\Eloquent\Builder Returns the modified query with applied date range.
     *
     * @throws \InvalidArgumentException If since or until dates are invalid.
     */
    public static function filterByDateRange($query, $targetField, $since, $until)
    {
        // Validate the dates
        if (!$since || !$until) {
            throw new \InvalidArgumentException('Both since and until dates are required.');
        }

        // Check if dates are valid formats
        if (!strtotime($since) || !strtotime($until)) {
            throw new \InvalidArgumentException('Invalid date format provided for since or until.');
        }

        // Apply the date range filter
        return $query->whereBetween($targetField, [$since, $until]);
    }


    /**
     * Send an email to one or multiple recipients with optional data and attachments.
     *
     * This function sends an email using the specified email address and data.
     * It supports sending to multiple recipients and includes error handling and logging.
     *
     * @param array|string $emails An email address or an array of email addresses to send the mail to.
     * @param array $mailData The data to be passed to the mail class.
     * @param array|null $attachments Optional array of file paths to be attached to the email.
     *
     * @return void
     *
     * @throws \Exception If there is an error during the email sending process.
     */
    public static function sendMail($emails, $mailData, $attachments = null)
    {
        try {
            // Ensure $emails is an array
            $emailArray = is_array($emails) ? $emails : [$emails];

            // Create the Mailable instance
            $mailable = new SendMail($mailData);

            // Attach files if provided
            if ($attachments) {
                foreach ($attachments as $attachment) {
                    $mailable->attach($attachment);
                }
            }

            // Send the email to each recipient
            foreach ($emailArray as $email) {
                Mail::to($email)->send($mailable);
            }
        } catch (\Exception $e) {
            // Log the error for further investigation
            \Log::error('Email sending failed', [
                'error' => $e->getMessage(),
                'emails' => $emailArray,
                'data' => $mailData,
            ]);
            throw new \Exception('Unable to send email. Please try again later.');
        }
    }

    /**
     * Convert local time to UTC
     *
     * @param  string $date
     * @param  boolean $format
     *
     * @return DateTime|string $date
     */
    public static function local_time_to_utc($date, $timezone = 'Asia/Jakarta', $return_string = false)
    {
        // $date = "2018-05-04 00:00:00";
        $date_local = new DateTime(date($date), new DateTimeZone($timezone));
        $date_utc = clone $date_local;
        $date_utc->setTimeZone(new DateTimeZone('UTC'));

        if ($return_string) {
            $date_utc = $date_utc->format('d-m-Y H:i:s');
        }

        return $date_utc;
    }

    /**
     * Convert UTC time to MySQL timestamp
     *
     * @param  string  $date_string   The UTC date string
     * @param  boolean $milliseconds  Whether to return the timestamp in milliseconds or not
     *
     * @return int|string  Timestamp in seconds or milliseconds, or a MySQL-compatible DATETIME string
     */
    public static function utc_time_to_timestamp($date_string, $milliseconds = true)
    {
        // Convert the input UTC date string to a UNIX timestamp (seconds since the Unix epoch)
        $since_utc_timestamp_int = strtotime($date_string);

        if ($since_utc_timestamp_int === false) {
            throw new Exception("Invalid date string provided.");
        }

        // If milliseconds flag is true, multiply the timestamp by 1000
        if ($milliseconds) {
            // Return the timestamp in milliseconds
            return $since_utc_timestamp_int * 1000;
        } else {
            // Return the timestamp in MySQL DATETIME format
            return date('Y-m-d H:i:s', $since_utc_timestamp_int);
        }
    }

    // UTC to Local Time
    public static function utc_to_local_time($date, $timezone = 'Asia/Jakarta', $format = 'Y-m-d H:i:s', $return_string = true)
    {
        // $date = "2018-05-04 00:00:00";
        $date_utc = new DateTime(date($date));
        $date_local = clone $date_utc;
        $date_local->setTimeZone(new DateTimeZone($timezone));

        if ($return_string) {
            $date_local = $date_local->format($format);
        }

        return $date_local;
    }

    /**
     * Get formatted date range between the provided dates.
     * 
     * This method returns a formatted date range between the given `startDate` and `endDate`.
     * If both are null, it defaults to the previous month and the current date.
     *
     * @param string|null $startDate The start date (since) in 'Y-m-d' format or null.
     * @param string|null $endDate The end date (until) in 'Y-m-d' format or null.
     * @return array An array with formatted start and end date strings.
     */
    public static function getFormattedDateRange(?string $startDate, ?string $endDate): array
    {
        if (is_null($startDate) && is_null($endDate)) {
            // Default to the last month and current date
            $startDate = $before_month;
            $endDate = $now;

            $formattedStartDate = self::utcToLocalTime($startDate, self::TIME_ZONE);
            $formattedEndDate = self::utcToLocalTime($endDate, self::TIME_ZONE);
        } else {
            // Add time to start and end date for full day range
            $formattedStartDate = "$startDate 00:00:00";
            $formattedEndDate = "$endDate 23:59:59";
        }

        return [$formattedStartDate, $formattedEndDate];
    }





    // DRY -> dont repeat yourself // 
}
