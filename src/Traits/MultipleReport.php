<?php

namespace Ajtarragona\Reports\Traits;


trait MultipleReport
{
   
    // abstract function columns();
    // abstract function rows();

   

    protected function prepareGroupedMultipleBody($rows, $columns, $parameters){
        $ret="";
        $groups=collect($rows)->groupBy($this->group_by)->sortKeys()->toArray();
        // dd($groups);
        //creo las vistas de cada row
        // $num_cols = $this->hide_grouped_column ?  $num_cols-1: $num_cols;
        if($this->hide_grouped_column && isset($columns[$this->group_by])){
            unset($columns[$this->group_by]);
        }
        foreach($groups as $group_title=>$group_rows){
            // dd($head_parameters);
            // dd($num_cols);
            
            if($this->viewExists('group_header')){
                $ret.=$this->view('group_header', array_merge($parameters, ['group_rows'=>$group_rows, 'columns'=>$columns,'group_title'=>$group_title]) )->render();
            }else{
                $ret.="<tr>";
                $ret.="    <th colspan='". count($columns)."' class='text-left'>";    
                $ret.="       <div class='bg-gray-400'>".$group_title."</div>";
                $ret.="    </th>";
                $ret.="</tr>";
                $ret.="<thead>";
                $ret.="    <tr>";
                if($columns){
                    foreach($columns as $column_key=>$column_label){
                            $ret.="    <th><div>".$column_label."</div></th>";
                    }
                }
                    
                $ret.="    </tr>";
                $ret.="</thead>";
            }

           
            foreach($group_rows as $i=>$group_row){
                // dump($this->hide_grouped_column, $group_row);
                if($this->hide_grouped_column && isset($group_row[$this->group_by])){
                    unset($group_row[$this->group_by]);
                }
                // dd($this->hide_grouped_column, $group_row);
                $args=array_merge($parameters,[
                    'row'=>$group_row,
                    'loop'=> to_object([
                        "index"=>$i+1,
                        "index_0"=>$i,
                        "first"=>$i==0,
                        "last"=> ($i== (count($this->rows)-1))
                    ])
                ],$group_row);
                
                // dd($args);
                $ret.=$this->view('row', $args )->render();
            }

            if($this->viewExists('group_footer')){
                $ret.=$this->view('group_footer', array_merge($parameters, ['group_rows'=>$group_rows, 'columns'=>$columns,'group_title'=>$group_title]) )->render();
            }else{
                $ret.="</table>";
                $ret.='<table class="table table-striped fullwidth">';

                // $ret.="<tfoot>";
                // $ret.="    <tr>";
                // $ret.="    <th colspan='".$num_cols."'><div>FOOT</div></th>";
                // $ret.="    </tr>";
                // $ret.="</tfoot>";
            }
        }
        return $ret;
    }
}
