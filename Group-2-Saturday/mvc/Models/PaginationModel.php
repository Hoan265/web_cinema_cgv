<?php

class PaginationModel extends DbConnection {
    public function pagination($query, $limit, $offset)
    {
        $sql = "$query LIMIT $offset, $limit";
        $result = mysqli_query($this->getConnection(), $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    function getTotalPages($query, $limit)
    {
        $result = mysqli_query($this->getConnection(), $query);
        $total_records = mysqli_num_rows($result);
        $total_pages = ceil($total_records / $limit);
        return $total_pages;
    }
}
?>