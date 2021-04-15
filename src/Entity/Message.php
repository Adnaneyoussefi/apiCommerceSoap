<?php


namespace App\Entity;


class Message
{

            private $code;
            private $msg;


            public function __construct(int $code , string $message)
            {
                $this->code = $code;
                $this->message = $message;
            }


            public function getCode(): ?int
            {
                return $this->code;
            }

            public function setId(int $code): self
            {
                $this->code = $code;

                return $this;
            }

            public function getMsg(): ?string
            {
                return $this->msg;
            }

            public function setMsg(string $msg): self
            {
                $this->msg = $msg;

                return $this;
            }

            public function __toString(): string
            {
                return $this->code;
            }

}

?>