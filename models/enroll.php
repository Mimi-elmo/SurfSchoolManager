<?php

require_once __DIR__ . '/lesson.php';

class Enroll
{
    private int $id;
    private int $student_id;
    private int $lesson_id;
    private string $pay_status;
    private string $lesson_title;
    private string $lesson_coach;
    private string $lesson_datetime;
    private float $lesson_price;

    public function __construct(array $data)
    {
        $this->id = (int) $data['id'];
        $this->student_id = (int) $data['student_id'];
        $this->lesson_id = (int) $data['lesson_id'];
        $this->pay_status = $data['pay_status'] ?? 'notpayd';

        $this->lesson_title = $data['title'] ?? '';
        $this->lesson_coach = $data['coach'] ?? '';
        $this->lesson_datetime = $data['datetime'] ?? '1970-01-01 00:00:00';
        $this->lesson_price = isset($data['price']) ? (float) $data['price'] : 0.0;
    }

    public function getLessonTitle(): string { return $this->lesson_title; }
    public function getLessonCoach(): string { return $this->lesson_coach; }
    public function getLessonPrice(): float { return $this->lesson_price; }
    public function getFormattedDate(): string
    {
        $date = new DateTime($this->lesson_datetime);
        return $date->format('d/m/Y H:i');
    }

    public function getPayStatus(): string { return $this->pay_status; }

    public static function findUpcomingByStudent(int $studentId): array
    {
        $pdo = Database::getConnection();

        $stmt = $pdo->prepare(
            'SELECT ls.*, l.title, l.coach, l.datetime, l.price
             FROM lesson_student ls
             JOIN lessons l ON ls.lesson_id = l.id
             WHERE ls.student_id = ?
               AND l.datetime >= NOW()
             ORDER BY l.datetime ASC'
        );

        $stmt->execute([$studentId]);

        $rows = $stmt->fetchAll();
        return array_map(fn($row) => new self($row), $rows);
    }
}
