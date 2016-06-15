<?php
/**
 * UserSearch class file.
 * @copyright (c) 2015, Pavel Bariev
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

namespace bariew\userModule\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use bariew\userModule\models\User;
 
/**
 * For searching companies.
 * 
 * 
 * @example
 * @author Pavel Bariev <bariew@yandex.ru>
 */
class CompanySearch extends Company
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'description', 'owner_id'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Searches users.
     * @param array $params search query data
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Company::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andWhere(['owner_id' => $this->owner_id])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ;

        return $dataProvider;
    }
}
