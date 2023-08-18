<?php

namespace App\Models\Booking;

use App\Assets\Timestamps;
use App\Models\Booking\Company;
use App\Models\Booking\ExtraCharge;
use App\Models\Booking\Payment;
use App\Models\Booking\Rent;
use App\Transformers\Booking\BookingTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes, Timestamps;

    public $table = "booking";

    public $view = "bookings";

    public $transformer = BookingTransformer::class;

    protected $fillable = [
        'code',
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
     * created_at y check_out, seran los dos campos a calcular
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
        $fechaInicio = strtotime($this->created_at);
        $fechaFin = strtotime($this->check_out);

        // Definir los límites de horas de inicio y fin del día
        $horaInicioDia = strtotime('13:00');
        $horaFinDia = strtotime('12:00 +1 day');

        // Si el usuario ingresa después de las 06:00 y 13:00 horas en adelante, su día vencerá al siguiente día a las 12:00 horas
        if (date('H:i', $fechaInicio) >= '06:00' && date('H:i', $fechaInicio) < '13:00') {
            $fechaInicio = $horaInicioDia;
        } elseif (date('H:i', $fechaInicio) >= '13:00') {
            $fechaInicio = strtotime('+1 day', $horaInicioDia); // Añadir un día al final del día para que venza al siguiente día a las 12:00 horas
        }

        // Si el usuario ingresa antes de las 06:00 horas, su horario vencerá el mismo día a las 12:00 horas, sumando un día más a su estadia
        if (date('H:i', $fechaFin) < '06:00') {
            $fechaFin = strtotime('+1 day', $horaFinDia); // Añadir un día al final del día para que venza al siguiente día a las 12:00 horas
        }

        $diferencia = $fechaFin - $fechaInicio;

        // Si la diferencia es menor o igual a cero, retornamos 1, ya que ningún día debe tener un valor de 0
        if ($diferencia <= 0) {
            return 1;
        }

        // Calculamos la cantidad de días en números enteros
        $dias = ceil($diferencia / (60 * 60 * 24));

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
}
