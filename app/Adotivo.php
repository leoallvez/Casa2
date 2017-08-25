<?php
namespace Casa;

use Carbon\Carbon;
use Casa\AdotivoStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Adotivo extends Model{
    use SoftDeletes;

    protected $dates = [
        'created_at',
        'nascimento',
        'data_chegada'
    ];

    protected $fillable = [
      'nome',
      'sexo',
      'etnia_id',
      'status_id',
      'matricula',
      'nascimento',
      'usuario_id',
      'restricao_id',
      'data_chegada',
      'instituicao_id',
      'escolaridade_id',
      'nacionalidade_id'
    ];
    /**
     * Retorna um array contendo outro array com nome do status e
     * a quantidade de adotivo nesse status.
     * @return array of array
     */
    public static function getQuantidadePorStatus() {
        $status = AdotivoStatus::all();

        foreach ($status as $s) {
            $resultado = DB::table('adotivos')
            ->join('adotivos_status', 'adotivos.status_id', '=', 'adotivos_status.id')
            ->select('adotivos_status.nome', DB::raw('count(adotivos.status_id) as quantidade'))
            ->where('adotivos_status.id', '=', $s->id)
            ->groupBy('adotivos_status.nome')
            ->first();

            if(isset($resultado)) {
                $dados[] = [$s->nome, intval($resultado->quantidade)];
            }else{
                $dados[] = [$s->nome, 0];
            }
        }
        return $dados;
    }
    public function setStatus(int $status_id) {
        $this->status_id = $status_id;
    }
    public function setInstituicao(int $id) {
        $this->instituicao_id = $id;
    }

    public function setUsuario(int $id) {
            $this->usuario_id = $id;
    }

    /**
     * Esse metodo retornar uma String com a idade de um adotivo.
     * @return string
     */
    public function calcularIdade() {
        $anos    = $this->nascimento->diffInYears(Carbon::now());
        $meses   = $this->nascimento->diffInMonths(Carbon::now());
        $semanas = $this->nascimento->diffInWeeks(Carbon::now());
        $dias    = $this->nascimento->diffInDays(Carbon::now());

        $idade = '';

        if( $anos > 0 && $anos > 1)
            $idade .= $anos.' anos ';
        else if($anos == 1 )
            $idade .= ' 1 ano ';

        if($meses > 0 && $meses < 13 && $meses > 1)
            $idade .= $meses.' meses ';
        else if($meses == 1 )
            $idade .= ' 1 mês';

        if($semanas > 0 && $semanas < 4 && $semanas > 1)
            $idade .= $semanas.' semanas ';
        else if($semanas == 1)
            $idade .= '1 semana ';

        if($dias > 0 && $dias < 7 && $dias > 1)
            $idade .= $dias.' dias';
        else if($dias == 1)
            $idade .= ' 1 dia';

        return $idade;
    }
    
    /**
     * Caso o adotivo, adotante e conjuge tenham uma diferença maior que 16 anos
     * retorna true, caso contrario false.
     * @return boolean
     */
    public function has16AnosDeDiferenca(Adotante $adotante) {
        $adotante_difference = $this->nascimento->diffInYears($adotante->nascimento);
        # Adotante pode não ter conjuge.
        if(!is_null($adotante->conjuge_nascimento)) {
            $conjuge_difference  = $this->nascimento->diffInYears($adotante->conjuge_nascimento);
            return $adotante_difference >= 16 && $conjuge_difference >= 16;
        }
        return $adotante_difference >= 16;
    }

    public function getSexo() {
        return ($this->sexo == 'M') ? 'Masculino' : 'Feminino';
    }

    public function getIrmaosIds() {
       return  $this->irmaos()->getRelatedIds()->toArray();
    }

    public function salvarImaos($irmaosIds) {
        if(isset( $irmaosIds )) {
            $this->irmaos()->sync($irmaosIds);
        } else {
            $this->irmaos()->sync([]);
        }
    }

    // public function saveIrmaos($irmaosIds) {
    //     if(isset( $irmaosIds )) {
    //         $this->irmaos()->attach($irmaosIds);
    //     }
    // }

    public function hasAdotantes() {

       $result = $this->adotantes()
       ->where('adotantes_adotivos.deleted_at', '=', null)
       ->get();

       return count($result) > 0;
    }

    public static function gerarMatricula() {

        $last_id = self::all()->last()->id;

        return ($last_id)? str_pad($last_id + 1 , 12, "CASA-00000000", STR_PAD_LEFT) : "CASA-00000001";
    }

    public function adotantes() {
    	return $this->belongsToMany('Casa\Adotante', 'adotantes_adotivos')
        ->withPivot('created_at', 'deleted_at');
    }

    public function status() {
        return $this->belongsTo('Casa\AdotivoStatus', 'status_id');
    }

    public function etnia() {
        return $this->belongsTo('Casa\Etnia');
    }

    public function nacionalidade() {
        return $this->belongsTo('Casa\Nacionalidade');
    }

    public function instituicao() {
        return $this->belongsTo('Casa\Instituicao');
    }

    public function visitas() {
        return $this->hasMany('Casa\Visita', 'adotivo_id');
    }

    public function restricao() {
        return $this->hasOne(
            'Casa\Restricao',
            'id',
            'restricao_id'
        );
    }

    public function irmaos() {
        return $this->belongsToMany(
            'Casa\Adotivo',
            'irmaos',
            'adotivo_id',
            'irmao_id'
        );
    }
}
