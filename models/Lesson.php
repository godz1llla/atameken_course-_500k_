<?php

class Lesson extends Model {
    protected $table = 'lessons';
    
    public function getByModule($moduleId) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE module_id = ? ORDER BY order_num");
        $stmt->execute([$moduleId]);
        return $stmt->fetchAll();
    }
    
    public function extractYoutubeId($url) {
        preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]+)/', $url, $matches);
        return isset($matches[1]) ? $matches[1] : null;
    }
    
    public function getYoutubeEmbedUrl($url) {
        $videoId = $this->extractYoutubeId($url);
        return $videoId ? "https://www.youtube.com/embed/" . $videoId : null;
    }
}

