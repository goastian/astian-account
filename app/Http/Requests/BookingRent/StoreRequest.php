<?php

namespace App\Http\Requests\BookingRent;

use App\Exceptions\ReportMessage;
use DateTime;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->can_access() and $this->can_not_update();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return request()->booking->is_reservation() ?
            $this->reservation_rules() :
            $this->booking_rules();
    }

    public function reservation_rules()
    {
        return [
            'room_id' => ['nullable', 'exists:rooms,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'integer'],
        ];
    }

    public function booking_rules()
    {
        return [
            'room_id' => ['required', 'exists:rooms,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'integer'],
        ];

    }

    /**
     * verifica que cuente con  algunos de los permisos
     */
    public function can_access(){
        return request()->user()->canWrite() || request()->user()->isAdmin();
    }

    /**
     * una vez hayan pasado 30 minutos desde el check_in no se podran
     * registrar mas habitacione
     *
     */
    public function can_not_update()
    {
        $last_time_for_add_room = strtotime(request('booking')->check_in . "+ " . env("LIMIT_TIME_BOOKING", 15) ." minutes");
         
        if (strtotime(now()) > $last_time_for_add_room) {
            $message = "ya han pasado " .env("LIMIT_TIME_BOOKING") . " minutos ";
            $message .= "y no puedes agregar mas habitaciones ";
            $message .= "pero puedes crear nuevos  registros";
            throw new ReportMessage(__($message), 403);
        }

        return true;
    }
}
