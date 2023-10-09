<?php

namespace App\Models\Booking;
 
use DateTime; 
use App\Models\Booking\Rent;
use App\Models\Assets\Calendar;
use App\Models\Booking\Company;
use App\Models\Booking\Payment;
use App\Models\Master as master; 
use App\Models\Booking\ExtraCharge;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Transformers\Booking\BookingTransformer;
use Elyerr\ApiExtend\Exceptions\ReportError;

class Booking extends master
{
    use SoftDeletes;

    public $table = "booking";

    public $view = "bookings";

    public $transformer = BookingTransformer::class;

    protected $fillable = [
        'code',
        'check_in',
        'check_out',
        'company_id',
        'type',
    ];

    protected $appends = [
        'days',
        'extra_charges',
        'total_room',
        'total',
        'to_pay',
        'paid',
        'refund',
        'company',
        'rooms',
    ];

    /**
     * calcular los dias entre dos fechas
     * check_in y check_out, seran los dos campos a calcular
     * las horas desde donde se calculara seran las siguientes las cuales seran valores
     * estaticos no modificables
     * hora de unicio o ingreso sera a partir de las 13:00 horas y vencera el siguiente dia a las
     * 12:00 horas, ahora se estableceran algunas reglas:
     * si el usuario ingresa despues de las 06:00 y 13:00 horas en adelante su dia vencera el siguiente dia a las 12:00 horas
     * si el usuario ingresa antes de las 06:00 horas, su horario vencera el mismo dia a las 12:00 horas, de
     * esta manera sumando un dia mas a su estadia
     * teniendo en cuenta esto cuando se vensa el horario se dara por cumplido un dia, ni un dia deve tener el valor 0, si el valor es cero la funcion retornara 1
     * en caso contrario retornara la cantidad de dias en numeros enteros
     *
     *
     */
    public function getDaysAttribute()
    {
        $check_out = new DateTime($this->check_out);
        $check_in = new DateTime($this->check_in);

        //inicializamos en ceros
        $dias = 0;
        //si ingresa antes de las 6:00 se le sumara un dia
        if (strtotime(date('H:i', strtotime($this->check_in))) < strtotime("06:00")) {
            $dias += 1;
        }

        //luego calculamos la diferencia
        $interval = $check_in->diff($check_out);

        $dias += (int) $interval->days;

        //para ajustar la funcion de php diff debemos comprobar que la hora despues de las 12:01
        //se incremente 1 al calculo
        if (strtotime(date('H:i', strtotime($this->check_in))) > strtotime("12:00")) {
            $dias += 1;
        }

        return $dias;
    }

    public function getTotalRoomAttribute()
    {
        return $this->rents()->get()->sum('price') * $this->days;
    }

    public function getToPayAttribute()
    {
        return ((($this->rents()->get()->sum('price')) * $this->days) + ($this->extra_chargeable()->get()->sum('total'))) - $this->paid;
    }

    public function getTotalAttribute()
    {
        return ($this->rents()->get()->sum('price') * $this->days) + $this->extra_chargeable()->get()->sum('total');
    }

    public function getExtraChargesAttribute()
    {
        return $this->extra_chargeable()->get()->sum('total');
    }

    public function getPaidAttribute()
    {
        return ($this->payments()->where('type', 'in')->get()->sum('price')) - $this->refund;
    }

    public function getRefundAttribute()
    {
        return ($this->payments()->where('type', 'out')->get()->sum('price'));
    }

    public function rents()
    {
        return $this->hasMany(Rent::class);
    }

    public function company()
    {
        return $this->BelongsTo(Company::class);
    }

    public function getCompanyAttribute()
    {
        return $this->company()->first();
    }

    public function getRoomsAttribute()
    {
        return $this->rents()->get();
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }

    public function extra_chargeable()
    {
        return $this->morphMany(ExtraCharge::class, 'extra_chargeable');
    }

    public function setCheckOutAttribute($value)
    {
        $check_out = strtotime($value);

        // Verificar si check_out tiene hora definida, si no, se agrega las 12:00 horas por defecto
        if (date('H:i', $check_out) === '00:00') {
            $check_out = strtotime(date('Y-m-d', $check_out) . ' 12:00');
        }

        $this->attributes['check_out'] = date('Y-m-d H:i', $check_out);
    }

    /**
     * verifica que sea una reserva
     */
    public function is_reservation()
    {
        return $this->type == "reservation";
    }

    /**
     * filtra el calendario por medio de categoria
     * @param String $category_id
     * @param String $check_in
     * @param String $check_out
     * @return Collection Calendar
     */
    public function get_calendar($category_id, $check_in, $check_out)
    {
        $check_in = date('Y-m-d', strtotime($check_in));
        $check_out = date('Y-m-d', strtotime($check_out . "- 1 days"));

        return Calendar::where('category_id', $category_id)
            ->whereBetween('day', [$check_in, $check_out])
            ->get();
    }

    /**
     * verifica que haya disponiblidad en el calendario
     * @param Calendar $calendar
     */
    public function can_not_update_calendar(Calendar $calendar)
    {
        if ($calendar->available < 1) {
            $message = "Por favor revise la disponiblidad, el dia $calendar->day no tiene";
            $message .= " habitaciones disponibles";
            throw new ReportError(__($message), 400);
        }
    }

    /**
     * verifica que el check_in haya empesado,
     * se utiliza el tiempo actual y y el checkin para verificar
     * @return Boolean
     */
    public function booking_start()
    {
        return strtotime(now()) >= strtotime($this->check_in);
    }
}
