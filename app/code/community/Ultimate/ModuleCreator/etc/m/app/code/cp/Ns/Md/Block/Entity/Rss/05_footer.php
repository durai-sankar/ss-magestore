			$description .= '</p>';
			$data = array(
				'title'=>$item->get{{EntityNameMagicCode}}(),
				'link'=>$item->get{{Entity}}Url(),
				'description' => $description
			);
			$rssObj->_addEntry($data);
		}
		return $rssObj->createRssXml();
	}
}