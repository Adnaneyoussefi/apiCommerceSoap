<?php


namespace App\Entity;


class Message
{
            private $code;
            private $msg;


            public function __construct(string $code , string $msg)
            {
                $this->code = $code;
                $this->msg = $msg;
            }


            public function getCode(): ?string
            {
                return $this->code;
            }

            public function setId(string $code): self
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