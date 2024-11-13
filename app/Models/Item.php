<?php
namespace App\Models;

use CodeIgniter\Model;

/**
 * ข้อมูลรายการจากตาราง "items"
 */
class Item extends Model
{
    protected $table = "items";
    protected $primaryKey = "id";
    protected $allowedFields = ["checked"];

    /**
     * อ่านข้อมูลจากตาราง
     * @param ?string $match ค้นหารายการที่ "title" มีคำนี้
     * @param ?int $limit จำนวนข้อมูลที่ต้องการ
     * @param ?int $offset ตำแหน่งเริ่มต้นต้นค้นหา
     * @param ?string $orderBy ชื่อฟิลด์ที่ตใช้สำหรับเรียงลำดับ
     * @param ?string $orderDir ASC หรือ DESC
     * @return array
     */
    public function getItems(?string $match, ?int $limit, ?int $offset, ?string $orderBy, ?string $orderDir): array
    {
        $t = $this->db->table($this->table)
            ->select('*')
            ->where("title LIKE '%" . $match. "%'")
            ->limit(!is_null($limit) ? $limit: null, !is_null($offset) ? $offset: 0);
        if(!is_null($orderBy) && !is_null($orderDir)) $t->orderBy($orderBy, strtoupper($orderDir));
        $result = $t->get()->getResult('array');
        $list = [];
        foreach ($result as $item) {
            array_push($list, [
                'id' => (int) $item['id'],
                'title' => $item['title'],
                'checked' => strcmp($item['checked'], '1') == 0
            ]);
        }
        return $list;
    }

    /**
     * นับจำนวนรายการทั้งหมด
     * @param ?string $query นับเฉพาะรายการที่ "title" มีคำนี้
     * @return int
     */
    public function getItemsCount(?string $query): int
    {
        $t = $this->db->table($this->table);
        if(is_null($query)) return $t->countAll();
        return (int)$t->select('COUNT(*) as count')->where("title LIKE '%" . $query . "%'")->get()->getRow('count');
    }

    /**
     * บันทึกค่า "checked" ของรายการ
     * @param int $id ไอดีของเรคคอร์ด
     * @param bool $checked ค่า "checked" ที่ต้องการบันทึก
     * @return bool
     */
    public function setChecked(int $id, bool $checked): bool
    {
        return $this->update($id, ['checked' => $checked]);
    }
}