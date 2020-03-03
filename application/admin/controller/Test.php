<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2019/3/18
 * Time: 15:13
 */
namespace app\admin\controller;

use think\Controller;

class Test extends Controller
{
    public function ok()
    {
        echo 'ok';
    }

    public function index()
    {
        $data = [1,1,2];
        $res = $this->removeDuplicates($data);
        de($res);
    }

    //从排序数组中删除重复项
    //给定一个排序数组，你需要在原地删除重复出现的元素，使得每个元素只出现一次，返回移除后数组的新长度。
    //不要使用额外的数组空间，你必须在原地修改输入数组并在使用 O(1) 额外空间的条件下完成。
    protected function removeDuplicates(&$data)
    {
        for($i=0;$i<count($data);$i++){
            if($data[$i]!=$data[$i++]){

            }
        }
        return $data;
    }

    //判断一个 9x9 的数独是否有效。只需要根据以下规则，验证已经填入的数字是否有效即可。
    //数字 1-9 在每一行只能出现一次。
    //数字 1-9 在每一列只能出现一次。
    //数字 1-9 在每一个以粗实线分隔的 3x3 宫内只能出现一次。
    protected function isValidSudoku($board){

    }

    //给定一个整数数组 nums 和一个目标值 target，请你在该数组中找出和为目标值的那 两个 整数，并返回他们的数组下标。
    //你可以假设每种输入只会对应一个答案。但是，你不能重复利用这个数组中同样的元素。
    protected function twoSum($nums, $target) {
        $count = count($nums);
        for($i=0;$i<$count;$i++){
            for($j=$count-1;$j>$i;$j--){
                if($nums[$i]+$nums[$j]==$target){
                    return [$i,$j];
                }
            }
        }
        return [];
    }

    //给定一个数组 nums，编写一个函数将所有 0 移动到数组的末尾，同时保持非零元素的相对顺序。
    protected function moveZeroes(&$nums){
        for($i=0;$i<count($nums);$i++){
            if($nums[$i]==0){
                $nums[] = $nums[$i];
                unset($nums[$i]);
            }
        }
        return array_values($nums);
    }

    //给定一个由整数组成的非空数组所表示的非负整数，在该数的基础上加一。
    //最高位数字存放在数组的首位， 数组中每个元素只存储一个数字。
    //你可以假设除了整数 0 之外，这个整数不会以零开头。
    protected function plusOne($digits) {
        $num = implode('',$digits);
        $b = '1';

        $lenA = strlen($num);
        $lenB = strlen($b);

        $j = 0;
        $re = '';
        for ($inxA = $lenA - 1, $inxB = $lenB - 1; ($inxA >= 0 || $inxB >= 0); --$inxA, --$inxB) {
            $itemA = ($inxA >= 0) ? (int)$num[$inxA] : 0;
            $itemB = ($inxB >= 0) ? (int)$b[$inxB] : 0;
            $sum = $itemA + $itemB + $j;
            if ($sum > 9) {
                $j = 1;
                $sum = $sum - 10;
            } else {
                $j = 0;
            }
            $re = (string)$sum . $re;
        }
        if ($j > 0) {
            $re = (string)$j . $re;
        }

        return str_split($re);
    }

    //给定两个数组，编写一个函数来计算它们的交集。
    protected function intersect($nums1, $nums2) {
        foreach ($nums1 as $k=>$v){
            if (!in_array($v,$nums2)){
                unset($nums1[$k]);
            }else{
                unset($nums2[array_search($v,$nums2)]);
            }
        }
        return $nums1;
    }

    //给定一个非空整数数组，除了某个元素只出现一次以外，其余每个元素均出现两次。找出那个只出现了一次的元素。
    //说明：你的算法应该具有线性时间复杂度。 你可以不使用额外空间来实现吗？
    protected function singleNumber($nums) {
        $num = 0;
        for($i=0;$i<count($nums);$i++){
            $num ^= $nums[$i];
        }
        return $num;
    }

    //给定一个整数数组，判断是否存在重复元素。
    //如果任何值在数组中出现至少两次，函数返回 true。如果数组中每个元素都不相同，则返回 false。
    protected function containsDuplicate($nums) {
        $new = [];
        foreach($nums as $v){
            if(in_array($v,$new)){
                return true;
            }else{
                $new[] = $v;
            }
        }
        return false;
    }

    //给定一个数组，将数组中的元素向右移动 k 个位置，其中 k 是非负数。
    //示例
    //输入: [1,2,3,4,5,6,7] 和 k = 3
    //输出: [5,6,7,1,2,3,4]
    protected function rotate(&$nums, $k) {
        $count = count($nums);
        for($i=1;$i<=$k;$i++){
            $a = $nums[$count-1];
            unset($nums[$count-1]);
            array_unshift($nums,$a);
        }
        return $nums;
    }

    //给定一个数组，它的第 i 个元素是一支给定股票第 i 天的价格。
    //设计一个算法来计算你所能获取的最大利润。你可以尽可能地完成更多的交易（多次买卖一支股票）。
    //注意：你不能同时参与多笔交易（你必须在再次购买前出售掉之前的股票）。
    protected function maxProfit($prices) {
        $max_profit = 0;
        for($i=1;$i<=count($prices);$i++){
            $d = $prices[$i] - $prices[$i-1];
            if($d>0){
                $max_profit += $d;
            }
        }
        return $max_profit;
    }

    //给定两个整数，被除数 dividend 和除数 divisor。将两数相除，要求不使用乘法、除法和 mod 运算符。
    //返回被除数 dividend 除以除数 divisor 得到的商。
    //被除数和除数均为 32 位有符号整数。
    //除数不为 0。
    //假设我们的环境只能存储 32 位有符号整数，其数值范围是 [−231,  231 − 1]。本题中，如果除法结果溢出，则返回 231 − 1。
    protected function divide($dividend, $divisor) {
        $is_plus = 1;
        if(($dividend<0 && $divisor>0) || ($dividend>0 && $divisor<0)){
            $is_plus = 0;
        }

        $dividend = abs($dividend);
        $divisor = abs($divisor);

        if($dividend==$divisor) return $is_plus?1:-1;
        if($dividend<$divisor) return 0;
        if($dividend==0) return 0;
        if($divisor==1) return $is_plus?$dividend:-$dividend;

        $res = $dividend;
        $i = 0;
        while($res>$divisor){
            $res = $res - $divisor;
            $i++;
        }
        return $is_plus?$i:-$i;
    }

    public function update()
    {
        $id = input('post.id');

        if($id>0)
        {
            //这里进入编辑文章逻辑

            $title = input('post.title');
            $content = input('post.content');
            $pub_time = input('post.pub_time');
            //上面的信息根据需求可能需要验证或处理

            $data = [
                'title' => $title,
                'content' => $content,
                'pub_time' => $pub_time
            ];

            model('admin/Article')->where('id','=',$id)->update($data);

            echo $id;
        }
        else
        {
            //这里则进入添加文章逻辑
        }
    }
}