	{if isset($orderId) && $orderId>0}
		<div>
			<p>Płatność została anulowana. Proszę skontaktować się z administratorem sklepu w celu wybrania innej metody płatności.<br> 
				Twój numer zamówienia: <strong> {$orderId}</strong>
			</p>
		</div>	
	
	{/if}