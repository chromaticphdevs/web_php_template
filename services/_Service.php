<?php 
	use Core\Database;
	use Core\DatabaseHelper;
	require_once 'core/Database.php';
	require_once 'core/DatabaseHelper.php';
	
	class _Service{

		protected $dbHelper;
		protected $databaseInstance;
		protected $databaseHelper;

		/*
		*must have in every extended service
		*/
		public $_tableName;
		public $_columnFillables = [];

		public function __construct() {
			$this->databaseInstance = new Database();
			$this->databaseHelper = new DatabaseHelper($this->databaseInstance);
		}

		protected function _getFillablesOnly($datas) {
			$return = [];

			foreach($datas as $key => $row) {
				$key = trim($key);
				if(in_array($key, $this->_columnFillables)) {
					$return[$key] = trim($row);
				}
			}
			return $return;
		}

		public function getAll($params = []) {
			$condition = !empty($params['where']) ? " WHERE ".$this->conditionConvert($params['where']) : '';
			$column = !empty($params['column']) ? "{$params['column']}" : '*';
			$order = !empty($params['order']) ? " ORDER BY {$params['order']}" : '';
			$limit = !empty($params['limit']) ? " LIMIT {$params['limit']}": '';
			return $this->databaseHelper->getAll($this->_tableName, $condition, $order, $limit, $column);
		}

		public function get($id) {
			$query = $this->databaseInstance->query("SELECT * FROM {$this->_tableName} WHERE id = '{$id}'");
			$retVal = [];

			while($row = $query->fetch_assoc()) {
				$retVal[] = $row;
			}

			return $retVal[0] ?? false;
		}

		public function single($params = []) {
			$where = $params['where'];

			$query = $this->databaseInstance->query(
				"SELECT * FROM {$this->_tableName}
					WHERE username = '{$where['username']}' "
			);

			$retVal = [];

			while($row = $query->fetch_assoc()) {
				$retVal[] = $row;
			}

			return $retVal[0] ?? false;
		}

		public function conditionConvert($params , $defaultCondition = '=')
		{
			$WHERE = '';
			$counter = 0;

			$errors = [];


			if(!is_array($params))
				return $params;
			/*
			*convert-where default concatinator is and
			*add concat on param values to use it
			*/
			$condition_operation_concatinator = 'AND';

			foreach($params as $key => $param_value) 
			{	
				if( $counter > 0)
					$WHERE .= " {$condition_operation_concatinator} "; //add space
				
				if($key == 'GROUP_CONDITION') {
					$WHERE .= '('.$this->conditionConvert($param_value) . ')';
					$counter++;
					continue;
				}
				/*should have a condition*/
				if(is_array($param_value) && isset($param_value['condition']) ) 
				{
					$condition_operation_concatinator = $param_value['concatinator'] ?? $condition_operation_concatinator;

					//check for what condition operation
					$condition = $param_value['condition'];
					$condition_values = $param_value['value'] ?? '';
					$isField = isset($param_value['is_field']) ? true : false;

					if(is_numeric($key) && isEqual($condition, $this->_dbConditionWrap)) {
						$WHERE .= "({$param_value['value']})";

						if(isset($param_value['concatinator'])) {
							$WHERE .= " {$param_value['concatinator']} ";
						}
						continue;
					}

					if(isEqual($condition , 'not null'))
					{
						$WHERE .= "{$key} IS NOT NULL ";
					}

					if( isEqual($condition , ['between' , 'not between']))
					{
						if( !is_array($condition_values) )
							return _error(["Invalid query" , $params]);
						if( count($condition_values) < 2 )
							return _error("Incorrect between condition");

						$condition = strtoupper($condition);

						list($valueA, $valueB) = $condition_values;
						if($isField) {
							$WHERE .= " {$key} {$condition} {$valueA} AND {$valueB}";
						}else{
							$WHERE .= " {$key} {$condition} '{$valueA}' AND '{$valueB}'";
						}
					}

					if( isEqual($condition , ['equal' , 'not equal' , 'in' , 'not in']) )
					{
						$conditionKeySign = '=';

						if( isEqual($condition , 'not equal') )
							$conditionKeySign = '!=';

						if( isEqual( $condition , 'in'))
							$conditionKeySign = ' IN ';

						if( isEqual( $condition , 'not in'))
							$conditionKeySign = ' NOT IN ';

						if( is_array($condition_values) ){
							if($isField) {
								$WHERE .= "{$key} $conditionKeySign (".implode(",",$condition_values).") ";
							}else{
								$WHERE .= "{$key} $conditionKeySign ('".implode("','",$condition_values)."') ";
							}
						}else{
							$WHERE .= "{$key} {$conditionKeySign} '{$condition_values}' ";
						}
					}

					/*
					*if using like
					*add '%' on value 
					*/
					if(isEqual($condition , ['>' , '>=' , '<' , '<=' , '=', 'like']) )
					{
						if($isField){
							$WHERE .= "{$key} {$condition} {$condition_values}";
						}else{
							$WHERE .= "{$key} {$condition} '{$condition_values}'";
						}
					}
					$counter++;

					continue;
				}

				if( isEqual($defaultCondition , 'like')) 
					$WHERE .= " $key {$defaultCondition} '%{$param_value}%'";

				if( isEqual($defaultCondition , '=')) 
				{
					$isNotCondition = substr( $param_value , 0 ,1); //get exlamation
					$isNotCondition = stripos($isNotCondition , '!');

					if( $isNotCondition === FALSE )
					{
						$WHERE .= " $key = '{$param_value}'";
					}else{
						
						$cleanRow = substr($param_value , 1);
						$WHERE .= " $key != '{$cleanRow}'";
					}
				}

				$counter++;
			}
			return $WHERE;
		}
	}